<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HealthConnect | Care Beyond Boundaries</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        html { scroll-behavior: smooth; }

        /* Navigation */
        nav { display: flex; justify-content: space-between; align-items: center; padding: 20px 8%; background: white; box-shadow: 0 2px 15px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 24px; font-weight: bold; color: #007bff; display: flex; align-items: center; gap: 10px; }
        .nav-links { display: flex; align-items: center; }
        .nav-links a { text-decoration: none; color: #333; margin-left: 30px; font-weight: 500; transition: 0.3s; }
        .nav-links a:hover { color: #007bff; }
        
        /* Dropdown Login Button Styling */
        .dropdown { position: relative; display: inline-block; margin-left: 30px; }
        .btn-login { background: #007bff; color: white !important; padding: 10px 25px; border-radius: 5px; border: none; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .btn-login:hover { background: #0056b3; }

        /* Dropdown Content (Hidden by Default) */
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.1);
            border-radius: 5px;
            z-index: 1001;
            overflow: hidden;
            border: 1px solid #eee;
        }

        .dropdown-content a {
            color: #333 !important;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            margin-left: 0 !important;
            font-size: 14px;
            border-bottom: 1px solid #f1f1f1;
        }

        .dropdown-content a:hover { background-color: #f8f9fa; color: #007bff !important; }
        .dropdown:hover .dropdown-content { display: block; }

        /* Hero Section */
        .hero { 
            height: 90vh; 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&w=1350&q=80'); 
            background-size: cover; background-position: center;
            display: flex; align-items: center; justify-content: center; text-align: center; color: white;
        }
        .hero-content h1 { font-size: 3.5rem; margin-bottom: 20px; }
        .hero-content p { font-size: 1.2rem; max-width: 800px; margin: 0 auto 30px; line-height: 1.6; }

        /* Section Styling */
        section { padding: 80px 10% ; }
        .section-title { text-align: center; margin-bottom: 50px; }
        .section-title h2 { font-size: 2.5rem; color: #2c3e50; }
        .section-title div { width: 50px; height: 4px; background: #007bff; margin: 15px auto; }

        /* What We Do */
        .feature-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px; }
        .feature-card { padding: 40px; border-radius: 15px; background: #f8f9fa; transition: 0.3s; text-align: center; border: 1px solid #eee; }
        .feature-card:hover { transform: translateY(-10px); background: white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }
        .feature-card h3 { color: #007bff; margin: 15px 0; }

        /* Contact Section */
        .contact-container { display: grid; grid-template-columns: 1fr 1fr; gap: 50px; }
        .contact-info h3 { margin-bottom: 20px; }
        .contact-form input, .contact-form textarea { width: 100%; padding: 12px; margin-bottom: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .contact-form button { padding: 12px 30px; background: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }

        footer { background: #2c3e50; color: white; text-align: center; padding: 40px; margin-top: 50px; }
    </style>
</head>
<body>

<nav>
    <div class="logo">✚ HealthConnect</div>
    <div class="nav-links">
        <a href="#about">About</a>
        <a href="#what-we-do">What We Do</a>
        <a href="#contact">Contact Us</a>
        
        <div class="dropdown">
            <button class="btn-login">Login / Sign Up ▾</button>
            <div class="dropdown-content">
                <a href="user_login.html">Patient Login</a>
                <a href="doctor_login.html">Doctor Login</a>
            </div>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="hero-content">
        <h1>Your Life is Our Mission.</h1>
        <p>"Health is the greatest gift, contentment the greatest wealth, faithfulness the best relationship." Connect with verified doctors instantly when life depends on it.</p>
        <a href="user_registration.html" style="background: #28a745; color:white; padding:15px 40px; text-decoration:none; border-radius:50px; font-weight:bold; display: inline-block;">Register Now</a>
    </div>
</section>

<section id="what-we-do">
    <div class="section-title">
        <h2>What We Do</h2>
        <div></div>
        <p>Bridging the gap between medical expertise and patients through technology.</p>
    </div>
    <div class="feature-grid">
        <div class="feature-card">
            <h2>🏥</h2>
            <h3>Expert Doctors</h3>
            <p>Access a wide network of specialized physicians ready to assist you at any time.</p>
        </div>
        <div class="feature-card">
            <h2>🚨</h2>
            <h3>SOS Emergency</h3>
            <p>One-tap emergency system that sends your GPS location and medical profile to a doctor.</p>
        </div>
        <div class="feature-card">
            <h2>📄</h2>
            <h3>Digital Records</h3>
            <p>Your blood group, age, and medical history are stored securely for faster treatment.</p>
        </div>
    </div>
</section>

<section id="contact" style="background: #fff;">
    <div class="contact-container">
        <div class="contact-info">
            <h2>Get In Touch</h2>
            <p>We are available 24/7 for support. Your feedback helps us save more lives.</p>
            <div style="margin-top: 30px;">
                <p>📍 <strong>Location:</strong>,Ghatkesar,Hyderabad,Telangana- 501301</p>
                <p>📧 <strong>Email:</strong>pranaykumar25@gmail.com</p>
                <p>📞 <strong>Emergency:</strong> 9912450839 </p>
            </div>
        </div>
        <form class="contact-form">
            <input type="text" placeholder="Your Name" required>
            <input type="email" placeholder="Your Email" required>
            <textarea rows="5" placeholder="Your Message" required></textarea>
            <button type="submit">Send Message</button>
        </form>
    </div>
</section>

<footer>
    <p>&copy; 2026 HealthConnect. Empowering Doctors. Protecting Life.</p>
</footer>

</body>
</html>