# Notes k předmětu PWAP
## _SESION - superglobal Array
- A **superglobal array** used to store session variables accessible across multiple pages during a user's session.
- Data persists on the server, tied to a unique session ID.
- přístupné odkudkoliv po inicializaci ``session_start()``
- [restart.php](./cviceni3/250311/restart.php)
    - smaže všechny proměnné z $_SESSION array!
## Key Features:
1. **Starting a Session:**
   - Call `session_start()` at the beginning of a PHP script.
   - PHP generates a unique session ID if one doesn’t exist.
   - The session ID is sent to the browser via a cookie (or URL) and is used to retrieve session data in later requests.

2. **Storing Data in a Session:**
   - Data is stored by assigning values to keys in the `$_SESSION` array.
   - Example:
     ```php
     $_SESSION["time"] = date("Y-M-d H:i:s");
     $_SESSION["location"] = "Brno";
     ```
   - Data is stored on the server and remains available until the session is destroyed or expires.

3. **Cross site persistance:**
   - In another file (e.g., `B.php`), call `session_start()` to resume the session.
   - Access the data using the same keys:
     ```php
     print_r($_SESSION);
     ```
