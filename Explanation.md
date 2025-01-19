<!DOCTYPE html>
<html lang="en">

<body>
    <h1>Overview</h1>
    <p>The "Cool Kids Network" project is a WordPress-based website designed to manage user registrations, role-based access, and character data through an intuitive interface and a secure API. It implements features for registering users, assigning roles, accessing data based on permissions, and managing roles via a protected REST API.</p>
 <h1>The Problem to Be Solved</h1>
    <p>The website needs to:</p>
    <ul>
        <li>Allow users to register and automatically generate a unique character for them.</li>
        <li>Enable logged-in users to view their character data.</li>
        <li>Implement role-based access control:</li>
        <ul>
            <li><strong>"Cooler Kid" role</strong>: Access to names and countries of all users.</li>
            <li><strong>"Coolest Kid" role</strong>: Access to all user data, including emails and roles.</li>
        </ul>
        <li>Provide a secure API endpoint for administrators to change user roles.</li>
    </ul>
    <h2>Technical Specification</h2>
    <h3>1. User Registration</h3>
    <p><strong>What happens:</strong></p>
    <ul>
        <li>Users sign up by providing an email address.</li>
        <li>A new WordPress user is created with the role "Cool Kid."</li>
        <li>Character data is generated using the RandomUser.me API and stored as user metadata.</li>
    </ul>
    <p><strong>How it works:</strong></p>
    <ul>
        <li>A custom registration form is created using a shortcode.</li>
        <li>WordPress functions like <code>wp_insert_user()</code> and <code>add_user_meta()</code> handle user creation and metadata storage.</li>
    </ul>
    <h3>2. User Login</h3>
    <p><strong>What happens:</strong></p>
    <ul>
        <li>Users log in using their email address.</li>
        <li>Upon successful login, they are redirected to a page displaying their character data.</li>
    </ul>
    <p><strong>How it works:</strong></p>
    <ul>
        <li>A custom login form is implemented using <code>wp_set_auth_cookie()</code> and <code>wp_set_current_user()</code> for authentication.</li>
        <li>Shortcodes display the logged-in user's character data.</li>
    </ul>
    <h3>3. Role-Based Access</h3>
    <p><strong>What happens:</strong></p>
    <ul>
        <li><strong>"Cooler Kid" users</strong> can view the names and countries of all users.</li>
        <li><strong>"Coolest Kid" users</strong> can view all user data, including emails and roles.</li>
    </ul>
    <p><strong>How it works:</strong></p>
    <ul>
        <li>Roles are defined in wp_usermeta as role meta_key in WordPress.</li>
        <li>Conditional logic based on <code>current_user_can()</code> checks user capabilities before granting access.</li>
        <li>Data is retrieved using <code>get_users()</code> and filtered appropriately.</li>
    </ul>
    <h3>4. Role Assignment via API</h3>
    <p><strong>What happens:</strong></p>
    <ul>
        <li>Administrators can send a POST request to assign a role to a user.</li>
        <li>Roles are restricted to "Cool Kid," "Cooler Kid," or "Coolest Kid."</li>
    </ul>
    <p><strong>How it works:</strong></p>
    <ul>
        <li>A REST API endpoint is registered with <code>register_rest_route()</code>.</li>
        <li>The API validates the request using <code>current_user_can('manage_options')</code>.</li>
        <li>User roles are updated using <code>set_role()</code>.</li>
    </ul>
    <h2>Design Decisions</h2>
    <ul>
        <li><strong>Why WordPress:</strong>
            <ul>
                <li>Familiar ecosystem with built-in user management.</li>
                <li>Flexible plugin architecture to implement custom features.</li>
            </ul>
        </li>
        <li><strong>RandomUser.me API:</strong>
            <ul>
                <li>Simplifies character data generation.</li>
                <li>Reduces development time for creating fake identities.</li>
            </ul>
        </li>
        <li><strong>Role-Based Access:</strong>
            <ul>
                <li>Built-in WordPress role system minimizes custom logic.</li>
            </ul>
        </li>
        <li><strong>REST API:</strong>
            <ul>
                <li>Ensures secure and scalable role management.</li>
            </ul>
        </li>
    </ul>
    <h2>How the Solution Meets the Requirements</h2>
    <ul>
        <li><strong>User Registration:</strong> Anonymous users can sign up and get an automatically generated character with metadata stored in the database.</li>
        <li><strong>User Login:</strong> Logged-in users can access their character data via a dedicated page.</li>
        <li><strong>Role-Based Access:</strong> Permissions ensure users only access data allowed by their roles.</li>
        <li><strong>Role Assignment via API:</strong> A secure endpoint allows admins to manage roles programmatically.</li>
    </ul>
    <h2>Setup Instructions</h2>
    <ul>
        <li><strong>Install WordPress:</strong> Set up a WordPress environment using tools like LocalWP, XAMPP, or Docker.</li>
        <li><strong>Create a Custom Plugin:</strong> Add all custom PHP code to a new plugin folder (e.g., <code>cool-kids-api</code>).</li>
        <li><strong>Register User Roles:</strong> Use <code>add_role()</code> to define roles: "Cool Kid," "Cooler Kid," and "Coolest Kid."</li>
        <li><strong>Set Up API Authentication:</strong> Install a plugin Basic Auth to secure the REST API.</li>
        <li><strong>Test the System:</strong> Verify registration, login, role-based access, and API functionality.</li>
    </ul>
<h2>Example API Request</h2>
    <p><strong>Endpoint:</strong> POST /index.php/wp-json/cool-kids/v1/assign-role</p>
    <pre>
    {
        "email": "c1@gmail.com",
        "role": "Cooler Kid"
    }
    </pre>
    <p><strong>Response:</strong></p>
    <pre>
    {"success":true,"message":"Role 'Cooler Kid' assigned to user r5@gmail.com."}
    </pre>
     <p><strong>Endpoint:</strong> POST /index.php/wp-json/cool-kids/v1/assign-role</p>
    <pre>
    {
    "role": "Cooler Kid",
    "first_name":"Marie",
    "last_name":"Jenkins"
    }
    </pre>
    <p><strong>Response:</strong></p>
    <pre>
    {"success":true,"message":"Role 'Cooler Kid' assigned to user r5@gmail.com."}
    </pre>
    <h2>Future Enhancements</h2>
    <ul>
        <li><strong>Password Implementation:</strong> Add secure password authentication for users.</li>
        <li><strong>Improved Frontend:</strong>  Create new theme with better UI.</li>
        <li><strong>Audit Logs:</strong> Log role changes for auditing purposes.</li>
        <li><strong>API Rate Limiting:</strong> Prevent abuse by limiting the number of requests per user.</li>
    </ul>
</body>
</html>
