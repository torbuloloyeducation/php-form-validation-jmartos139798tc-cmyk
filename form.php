<?php
// error variables
$nameErr = $emailErr = $genderErr = $phoneErr = $websiteErr = "";
$passwordErr = $confirmPasswordErr = $termsErr = "";

// field value variables 
$name = $email = $website = $comment = $gender = $phone = "";
$password = $confirmPassword = "";
$terms = false;

$submitted = false;

$attemptCount = isset($_POST["attempt_count"]) ? (int)$_POST["attempt_count"] : 0;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;
    $attemptCount++; 

    //name
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z\-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }

    //email
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }

    //phone
    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match("/^[+]?[0-9 \-]{7,15}$/", $phone)) {
            $phoneErr = "Invalid phone format";
        }
    }

    //website
    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format (e.g., https://www.example.com)";
        }
    }

    //comment
    $comment = empty($_POST["comment"]) ? "" : test_input($_POST["comment"]);

    // Gender validation 
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
    }

    //password
    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = $_POST["password"]; // Do not sanitize for comparison
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        }
    }

    //confirm password
    if (empty($_POST["confirm_password"])) {
        $confirmPasswordErr = "Please confirm your password";
    } else {
        $confirmPassword = $_POST["confirm_password"];
        if (empty($passwordErr) && $confirmPassword !== $password) {
            $confirmPasswordErr = "Passwords do not match";
        }
    }

    // terms and condition
    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    } else {
        $terms = true;
    }
}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Form is only valid when all required fields pass with no errors
$formValid = $submitted
    && empty($nameErr)
    && empty($emailErr)
    && empty($phoneErr)
    && empty($websiteErr)
    && empty($genderErr)
    && empty($passwordErr)
    && empty($confirmPasswordErr)
    && empty($termsErr);


?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modern PHP Form</title>
    <style>
      :root {
    --primary-color: #4f46e5;
    --primary-hover: #4338ca;
    --bg-color: #f9fafb;
    --card-bg: #ffffff;
    --text-main: #1f2937;
    --text-muted: #6b7280;
    --error-red: #ef4444;
    --success-green: #10b981;
    --border-color: #e5e7eb;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: var(--bg-color);
    color: var(--text-main);
    line-height: 1.5;
    margin: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
}

.form-container {
    background: var(--card-bg);
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    width: 100%;
    max-width: 500px;
}

h2 {
    margin: 0 0 8px 0;
    font-size: 1.5rem;
    font-weight: 700;
    color: #111827;
}

.field-row {
    margin-bottom: 20px;
    display: flex;
    flex-direction: column;
}

label {
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 6px;
    display: block;
}

/* Password and Textarea */
input[type="text"], 
input[type="password"],
textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border-color);
    border-radius: 6px;
    font-size: 1rem;
    box-sizing: border-box;
    transition: border-color 0.2s, box-shadow 0.2s;
}

/* Red border if error exists */
.has-error {
    border-color: var(--error-red) !important;
}

.radio-group {
    display: flex;
    gap: 15px;
    margin-top: 5px;
}

/* Divider for the password section */
.divider {
    border: none;
    border-top: 1px solid var(--border-color);
    margin: 20px 0;
}

button[type="submit"] {
    width: 100%;
    background-color: var(--primary-color);
    color: white;
    border: none;
    padding: 12px;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background-color 0.2s;
}

button[type="submit"]:hover {
    background-color: var(--primary-hover);
}

.error {
    color: var(--error-red);
    font-size: 0.8rem;
    margin-top: 4px;
}

.success-box, .output-box {
    margin-top: 24px;
    padding: 16px;
    border-radius: 8px;
    font-size: 0.95rem;
}

.success-box {
    background-color: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
}

.output-box {
    background-color: #f3f4f6;
    border: 1px solid var(--border-color);
}
    </style>
  </head>
  <body>
    <div class="form-container">
    <h2>Get in Touch</h2>
    <p class="required-note">Fields marked with <span style="color:var(--error-red)">*</span> are required.</p>

    <!-- Exercise 5: Submission attempt counter -->
    <p class="attempt-counter">Submission attempt: <strong><?= $attemptCount ?></strong></p>

    <?php if ($formValid): ?>
        <div class="success-box">&#10003; Form submitted successfully!</div>
    <?php endif; ?>

    <form method="post" action="<?= htmlspecialchars($_SERVER["PHP_SELF"]) ?>">

        <input type="hidden" name="attempt_count" value="<?= $attemptCount ?>">

        <!-- Name -->
        <div class="field-row">
            <label for="name">Name <span style="color:var(--error-red)">*</span></label>
            <input type="text" id="name" name="name"
                   placeholder="Jane Doe"
                   value="<?= $name ?>"
                   class="<?= $nameErr ? 'has-error' : '' ?>">
            <?php if ($nameErr): ?><span class="error"><?= $nameErr ?></span><?php endif; ?>
        </div>

        <!-- Email -->
        <div class="field-row">
            <label for="email">E-mail <span style="color:var(--error-red)">*</span></label>
            <input type="text" id="email" name="email"
                   placeholder="jane@example.com"
                   value="<?= $email ?>"
                   class="<?= $emailErr ? 'has-error' : '' ?>">
            <?php if ($emailErr): ?><span class="error"><?= $emailErr ?></span><?php endif; ?>
        </div>

        <!-- Exercise 1: Add a Phone Number Field  -->
        <div class="field-row">
            <label for="phone">Phone Number <span style="color:var(--error-red)">*</span></label>
            <input type="text" id="phone" name="phone"
                   placeholder="+1 555-123-4567"
                   value="<?= $phone ?>"
                   class="<?= $phoneErr ? 'has-error' : '' ?>">
            <?php if ($phoneErr): ?><span class="error"><?= $phoneErr ?></span><?php endif; ?>
        </div>

        <!-- Exercise 2: Validate the Website Field  -->
        <div class="field-row">
            <label for="website">Website</label>
            <input type="text" id="website" name="website"
                   placeholder="https://..."
                   value="<?= $website ?>"
                   class="<?= $websiteErr ? 'has-error' : '' ?>">
            <?php if ($websiteErr): ?><span class="error"><?= $websiteErr ?></span><?php endif; ?>
        </div>

        <!-- Comment -->
        <div class="field-row">
            <label for="comment">Comment</label>
            <textarea id="comment" name="comment"
                      placeholder="Tell us more..."
                      rows="3"><?= $comment ?></textarea>
        </div>

        <!-- Gender -->
        <div class="field-row">
            <label>Gender <span style="color:var(--error-red)">*</span></label>
            <div class="radio-group">
                <label class="radio-item">
                    <input type="radio" name="gender" value="Female"
                           <?= ($gender == "Female") ? "checked" : "" ?>> Female
                </label>
                <label class="radio-item">
                    <input type="radio" name="gender" value="Male"
                           <?= ($gender == "Male") ? "checked" : "" ?>> Male
                </label>
                <label class="radio-item">
                    <input type="radio" name="gender" value="Other"
                           <?= ($gender == "Other") ? "checked" : "" ?>> Other
                </label>
            </div>
            <?php if ($genderErr): ?><span class="error"><?= $genderErr ?></span><?php endif; ?>
        </div>

        <!-- Exercise 3: Add a Password Field with Confirmation --> 

        <div class="field-row">
            <label for="password">Password <span style="color:var(--error-red)">*</span></label>
            <input type="password" id="password" name="password"
                   placeholder="At least 8 characters"
                   class="<?= $passwordErr ? 'has-error' : '' ?>">

            <label style="margin-top:5px; font-size:0.9rem;">
            <input type="checkbox" onclick="togglePassword('password', this)"> Show Password
            </label>

            <?php if ($passwordErr): ?><span class="error"><?= $passwordErr ?></span><?php endif; ?>
        </div>

        <div class="field-row">
            <label for="confirm_password">Confirm Password <span style="color:var(--error-red)">*</span></label>
            <input type="password" id="confirm_password" name="confirm_password"
                   placeholder="Repeat your password"
                   class="<?= $confirmPasswordErr ? 'has-error' : '' ?>">

            <label style="margin-top:5px; font-size:0.9rem;">
            <input type="checkbox" onclick="togglePassword('confirm_password', this)"> Show Password
            </label>

            <?php if ($confirmPasswordErr): ?><span class="error"><?= $confirmPasswordErr ?></span><?php endif; ?>
        </div>

        <hr class="divider">

        <!-- Exercise 4: Exercise 3: Add a Password Field with Confirmation -->  
        <div class="field-row">
            <label class="checkbox-row">
                <input type="checkbox" name="terms" <?= $terms ? "checked" : "" ?>>
                <span>I agree to the <a href="" class="terms-link">Terms and Conditions</a></span>
            </label>
            <?php if ($termsErr): ?><span class="error"><?= $termsErr ?></span><?php endif; ?>
        </div>

        <button type="submit">Send Message</button>
    </form>

    <!-- Results output box -->
    <div class="output-box">
        <?php if ($submitted && $formValid): ?>
            <h3>Your Input:</h3>
            <p><strong>Name:</strong> <?= $name ?></p>
            <p><strong>E-mail:</strong> <?= $email ?></p>
            <p><strong>Phone:</strong> <?= $phone ?></p>
            <?php if (!empty($website)): ?>
                <p><strong>Website:</strong> <?= $website ?></p>
            <?php endif; ?>
            <p><strong>Gender:</strong> <?= $gender ?></p>
            <?php if (!empty($comment)): ?>
                <p><strong>Comment:</strong> <?= $comment ?></p>
            <?php endif; ?>
            <p style="margin-top:8px; font-size:0.85rem; color:#9ca3af;">(Password not displayed for security)</p>
        <?php elseif ($submitted && !$formValid): ?>
            <p style="color:var(--error-red); margin:0;">Please fix the errors and try again.</p>
        <?php else: ?>
            <p style="margin:0; font-style:italic; color:#9ca3af;">Results will appear here after a successful submission.</p>
        <?php endif; ?>
    </div>
</div>
  </body>

<script>

    if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}

function togglePassword(inputId, checkbox) {
    const input = document.getElementById(inputId);
    if (checkbox.checked) {
        input.type = "text";
    } else {
        input.type = "password";
    }
}
</script>

</html>
