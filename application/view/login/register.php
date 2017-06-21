<div class="container" style="max-width: 650px;">

    <!-- echo out the system feedback (error and success messages) -->
    <?php $this->renderFeedbackMessages(); ?>

    <h2>Register a new account</h2>

    <form method="post" action="<?php echo Config::get('URL'); ?>login/register_action">
        <div class="form-group">
            <label for="user_name">Username</label>
            <input type="text" class="form-control" pattern="[a-zA-Z0-9]{2,64}" name="user_name" placeholder="Username (letters/numbers, 2-64 chars)" required />
        </div>
        <div class="form-group">
            <label for="user_email">Email Address</label>
            <input type="text" class="form-control" name="user_email" placeholder="email address (a real address)" required />
        </div>
        <div class="form-group">
            <label for="user_password_new">Password</label>
            <input type="password" class="form-control" name="user_password_new" pattern=".{6,}" placeholder="Password (6+ characters)" required autocomplete="off" />
        </div>
        <div class="form-group">
            <label for="user_password_repeat">Repeat Your Password</label>
            <input type="password" class="form-control" name="user_password_repeat" pattern=".{6,}" required placeholder="Repeat your password" autocomplete="off" />
        </div>
        <div class="form-group">
            <label for="user_language">Language</label>
            <select class="form-control" name="user_language">
              <option value="en">English</option>
            </select>
        </div>
        <div class="form-group">
            <label for="user_organization">Organization</label>
            <input type="text" class="form-control" name="user_organization" placeholder="Organization name" />
        </div>
        <div class="form-group">
            <label for="user_facebook">Organization Facebook</label>
            <input type="text" class="form-control" name="user_facebook" placeholder="Organization Facebook" />
        </div>
        <div class="form-group">
            <label for="user_twitter">Organization Twitter</label>
            <input type="text" class="form-control" name="user_twitter" placeholder="Organization Twitter" />
        </div>
        <div class="form-group">
            <label for="captcha">Captcha</label><br />
            <!-- show the captcha by calling the login/showCaptcha-method in the src attribute of the img tag -->
            <img id="captcha" src="<?php echo Config::get('URL'); ?>login/showCaptcha" /><br />
            <input type="text" class="form-control" name="captcha" placeholder="Please enter above characters" required />
            <!-- quick & dirty captcha reloader -->
            <a href="#" style="display: block; font-size: 11px; margin: 5px 0 15px 0; text-align: center"
               onclick="document.getElementById('captcha').src = '<?php echo Config::get('URL'); ?>login/showCaptcha?' + Math.random(); return false">Reload Captcha</a>
       </div>
       <button type="submit" class="btn btn-default">Register</button>
    </form>
</div>
