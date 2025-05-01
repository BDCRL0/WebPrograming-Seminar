<!-- contact.php -->
<link rel="stylesheet" href="assets/css/style.css">

<div class="back" id="contact">
    <div class="container">
        <h1>Contact Us</h1>
        <div class="imgmenu">
            <img src="assets/images/2.png" alt="Logo">
        </div>

        <div class="contact-form">
            <form action="#" method="post" id="contactForm">
                <input type="text" name="name" placeholder="Your Name*" required>
                <input type="email" name="email" placeholder="Your Email*" required>
                <input type="text" name="subject" placeholder="Subject" required>
                <textarea name="message" placeholder="Your Message*" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </div>

        <!-- Success message placeholder -->
        <div class="success-message" style="display:none;">
            <h3>Thank you for contacting us!</h3>
        </div>
    </div>
</div>
