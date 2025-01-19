<?php
use PHPUnit\Framework\TestCase;

class AssignUserRoleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Mock WordPress functions
        if (!function_exists('sanitize_email')) {
            function sanitize_email($email)
            {
                return filter_var($email, FILTER_SANITIZE_EMAIL);
            }
        }

        if (!function_exists('sanitize_text_field')) {
            function sanitize_text_field($text)
            {
                return filter_var($text, FILTER_SANITIZE_STRING);
            }
        }

        if (!function_exists('get_user_by')) {
            function get_user_by($field, $value)
            {
                global $mock_users;
                return $mock_users[$value] ?? null;
            }
        }

        if (!function_exists('get_users')) {
            function get_users($args)
            {
                global $mock_users;
                return array_values($mock_users);
            }
        }

        if (!function_exists('update_user_meta')) {
            function update_user_meta($user_id, $meta_key, $meta_value)
            {
                global $mock_user_meta;
                $mock_user_meta[$user_id][$meta_key] = $meta_value;
                return true;
            }
        }
    }

    public function testAssignRoleWithEmail()
    {
        global $mock_users, $mock_user_meta;

        $mock_users = [
            'test@example.com' => (object) [
                'ID' => 1,
                'user_email' => 'test@example.com',
            ],
        ];

        $mock_user_meta = [];

        $request = $this->createMock(\WP_REST_Request::class);
        $request->method('get_param')
            ->willReturnMap([
                ['email', 'test@example.com'],
                ['role', 'Cool Kid'],
                ['first_name', null],
                ['last_name', null],
            ]);

        $response = assign_user_role($request);

        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertEquals("Role 'Cool Kid' assigned to user test@example.com.", $response['message']);
        $this->assertEquals('Cool Kid', $mock_user_meta[1]['role']);
    }

    public function testAssignRoleWithInvalidRole()
    {
        $request = $this->createMock(\WP_REST_Request::class);
        $request->method('get_param')
            ->willReturnMap([
                ['email', 'test@example.com'],
                ['role', 'Invalid Role'],
                ['first_name', null],
                ['last_name', null],
            ]);

        $response = assign_user_role($request);

        $this->assertInstanceOf(WP_Error::class, $response);
        $this->assertEquals('invalid_role', $response->get_error_code());
        $this->assertEquals('Invalid role specified.', $response->get_error_message());
    }

    public function testAssignRoleWithMissingParams()
    {
        $request = $this->createMock(\WP_REST_Request::class);
        $request->method('get_param')
            ->willReturnMap([
                ['email', null],
                ['role', 'Cool Kid'],
                ['first_name', null],
                ['last_name', null],
            ]);

        $response = assign_user_role($request);

        $this->assertInstanceOf(WP_Error::class, $response);
        $this->assertEquals('missing_params', $response->get_error_code());
        $this->assertEquals('Email or first and last name must be provided.', $response->get_error_message());
    }

    public function testAssignRoleWithFirstNameAndLastName()
    {
        global $mock_users, $mock_user_meta;

        $mock_users = [
            'user1' => (object) [
                'ID' => 2,
                'user_email' => 'user1@example.com',
            ],
        ];

        $mock_user_meta = [];

        $request = $this->createMock(\WP_REST_Request::class);
        $request->method('get_param')
            ->willReturnMap([
                ['email', null],
                ['role', 'Cool Kid'],
                ['first_name', 'John'],
                ['last_name', 'Doe'],
            ]);

        $response = assign_user_role($request);

        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
    }
}

?>
