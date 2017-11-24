# YouUp Webhook Parser

Add support to your favorite tools in YouUp.

## How?

### Step 1: Scaffold

Run `php cli scaffold` and follow the instruction.

### Step 2: Capture an example of a request

Run `php -S localhost:9999 receiver.php` to start a piece of code that will capture the incoming request.
Use tools such a ngrok, to get public URL that will point to your machine.
After a request, the terminal will show the text to copy paste into the `NAME_test_request.json` file.

### Step 3: Complete NAME_parsed_results.json
This file contain the data that should be extracted from the test request and used by YouUp.


### Step 4: Write the logic of NAME.php
2 methods must be implemented:
- isMatch => detect that an incoming webhook request should be handle by this class
- extract => read the information from the request

### Step 5: Pull Request
Submit a pull request, and once merged, the new integration will be live in YouUp. 
