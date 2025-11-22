<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio | Strawberry Matcha Theme</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --strawberry: #FF6B8B;
            --matcha: #A7D8A8;
            --light-matcha: #D4EDDA;
            --dark-matcha: #7CB083;
            --cream: #FFF9F0;
            --dark-text: #2C3E50;
            --light-text: #5D6D7E;
            --accent: #FF9EB5;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Open Sans', sans-serif;
            color: var(--dark-text);
            line-height: 1.6;
            background-color: var(--cream);
        }
        
        h1, h2, h3, h4 {
            font-family: 'Montserrat', sans-serif;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        /* Header */
        .portfolio-header {
            background-color: var(--matcha);
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 3px solid var(--strawberry);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .portfolio-logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark-text);
        }
        
        .portfolio-logo span {
            color: var(--strawberry);
        }
        
        .nav-menu {
            display: flex;
            gap: 25px;
        }
        
        .nav-link {
            text-decoration: none;
            color: var(--dark-text);
            font-weight: 600;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-link:hover {
            color: var(--strawberry);
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--strawberry);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--dark-text);
            cursor: pointer;
        }
        
        /* Hero Section */
        .hero-section {
            padding: 100px 0;
            background: 
                radial-gradient(circle at 20% 80%, rgba(255, 107, 139, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(167, 216, 168, 0.1) 0%, transparent 50%),
                linear-gradient(135deg, var(--cream) 0%, var(--light-matcha) 100%);
            position: relative;
            overflow: hidden;
        }

        .hero-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
        }

        .hero-text h1 {
            font-size: 3rem;
            line-height: 1.1;
            margin-bottom: 20px;
            color: var(--dark-text);
            font-weight: 800;
        }
        
        .hero-text h1 span {
            color: var(--strawberry);
            display: block;
        }

        .hero-text p {
            font-size: 1.3rem;
            margin-bottom: 30px;
            color: var(--light-text);
            font-weight: 500;
        }
        
        .hero-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--strawberry), var(--accent));
            color: white;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1.1rem;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(255, 107, 139, 0.4);
        }
        
        .hero-image {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            position: relative;
            border: 3px solid var(--strawberry);
        }
        
        .hero-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s;
        }
        
        .hero-image:hover img {
            transform: scale(1.05);
        }
        
        .btn {
            display: inline-block;
            padding: 14px 32px;
            border-radius: 6px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            text-align: center;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--strawberry), var(--accent));
            color: white;
            box-shadow: 0 5px 15px rgba(255, 107, 139, 0.4);
        }
        
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(255, 107, 139, 0.6);
        }
        
        .btn-secondary {
            background-color: transparent;
            color: var(--dark-text);
            border: 2px solid var(--strawberry);
        }
        
        .btn-secondary:hover {
            background-color: var(--strawberry);
            color: white;
        }
        
        /* About Section */
        .about-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--light-matcha) 0%, var(--cream) 100%);
            position: relative;
            overflow: hidden;
        }

        .about-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--strawberry), var(--accent));
        }

        .about-section h2 {
            text-align: center;
            margin-bottom: 50px;
            font-size: 2.5rem;
            color: var(--dark-text);
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }

        .about-section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--strawberry), var(--accent));
            border-radius: 2px;
        }

        .about-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .about-text p {
            margin-bottom: 20px;
            color: var(--light-text);
            font-size: 1.1rem;
        }

        .skills-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .skill-item {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            border-left: 4px solid var(--strawberry);
            transition: transform 0.3s ease;
        }

        .skill-item:hover {
            transform: translateY(-5px);
        }

        .skill-item h4 {
            color: var(--dark-text);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .skill-item h4 i {
            color: var(--strawberry);
        }

        .skill-bar {
            height: 8px;
            background: var(--light-matcha);
            border-radius: 4px;
            margin-top: 10px;
            overflow: hidden;
        }

        .skill-progress {
            height: 100%;
            background: linear-gradient(90deg, var(--strawberry), var(--accent));
            border-radius: 4px;
        }
        
        /* Portfolio Section */
        .portfolio-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--cream) 0%, var(--light-matcha) 100%);
            position: relative;
            overflow: hidden;
        }

        .portfolio-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--strawberry), var(--accent));
        }

        .portfolio-section h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5rem;
            color: var(--dark-text);
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }

        .portfolio-section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--strawberry), var(--accent));
            border-radius: 2px;
        }

        .portfolio-section > .container > p {
            text-align: center;
            font-size: 1.2rem;
            color: var(--light-text);
            margin-bottom: 50px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .portfolio-filter {
            display: flex;
            justify-content: center;
            margin-bottom: 40px;
            gap: 15px;
            flex-wrap: wrap;
        }

        .filter-btn {
            background: white;
            color: var(--light-text);
            border: 2px solid var(--light-matcha);
            padding: 10px 20px;
            border-radius: 30px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .filter-btn:hover, .filter-btn.active {
            background: var(--strawberry);
            color: white;
            border-color: var(--strawberry);
        }

        .portfolio-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 30px;
        }

        .portfolio-item {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .portfolio-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(255, 107, 139, 0.2);
        }

        .portfolio-image {
            position: relative;
            overflow: hidden;
            height: 250px;
        }

        .portfolio-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .portfolio-item:hover .portfolio-image img {
            transform: scale(1.1);
        }

        .portfolio-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 107, 139, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .portfolio-item:hover .portfolio-overlay {
            opacity: 1;
        }

        .portfolio-links {
            display: flex;
            gap: 15px;
        }

        .portfolio-link {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: white;
            color: var(--strawberry);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .portfolio-link:hover {
            background: var(--dark-text);
            color: white;
            transform: scale(1.1);
        }

        .portfolio-info {
            padding: 20px;
        }

        .portfolio-info h3 {
            color: var(--dark-text);
            margin-bottom: 10px;
            font-size: 1.3rem;
        }

        .portfolio-info p {
            color: var(--light-text);
            margin-bottom: 15px;
        }

        .portfolio-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .portfolio-tag {
            background: var(--light-matcha);
            color: var(--dark-matcha);
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
        }
        
        /* Contact Section */
        .contact-section {
            padding: 80px 0;
            background: linear-gradient(135deg, var(--light-matcha) 0%, var(--cream) 100%);
            position: relative;
            overflow: hidden;
        }

        .contact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--strawberry), var(--accent));
        }

        .contact-section h2 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 2.5rem;
            color: var(--dark-text);
            position: relative;
            display: inline-block;
            left: 50%;
            transform: translateX(-50%);
        }

        .contact-section h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, var(--strawberry), var(--accent));
            border-radius: 2px;
        }

        .contact-section > .container > p {
            text-align: center;
            font-size: 1.2rem;
            color: var(--light-text);
            margin-bottom: 50px;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .contact-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--strawberry);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .contact-details h4 {
            color: var(--dark-text);
            margin-bottom: 5px;
        }

        .contact-details p {
            color: var(--light-text);
        }

        .social-links {
            display: flex;
            gap: 15px;
            margin-top: 10px;
        }

        .social-link {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light-matcha);
            color: var(--dark-text);
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .social-link:hover {
            background: var(--strawberry);
            color: white;
            transform: translateY(-3px);
        }

        .contact-form {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-text);
        }

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid var(--light-matcha);
            border-radius: 8px;
            background: white;
            color: var(--dark-text);
            font-size: 1rem;
            transition: all 0.3s;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--strawberry);
            box-shadow: 0 0 0 3px rgba(255, 107, 139, 0.2);
        }

        .form-group textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        /* Footer */
        .portfolio-footer {
            background-color: var(--matcha);
            color: var(--dark-text);
            padding: 40px 0 20px;
            text-align: center;
        }
        
        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }
        
        .footer-logo {
            font-family: 'Montserrat', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--dark-text);
        }
        
        .footer-logo span {
            color: var(--strawberry);
        }
        
        .footer-links {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .footer-link {
            color: var(--dark-text);
            text-decoration: none;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: var(--strawberry);
        }
        
        .footer-social {
            display: flex;
            gap: 15px;
        }
        
        .footer-social a {
            color: var(--dark-text);
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }
        
        .footer-social a:hover {
            color: var(--strawberry);
        }
        
        .copyright {
            margin-top: 20px;
            color: var(--light-text);
            font-size: 0.9rem;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .hero-content, .about-content, .contact-content {
                grid-template-columns: 1fr;
            }
            
            .portfolio-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .hero-text, .about-text {
                text-align: center;
            }
            
            .hero-text h1 {
                font-size: 2.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--matcha);
                flex-direction: column;
                padding: 20px;
                box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
                z-index: 1000;
            }
            
            .nav-menu.active {
                display: flex;
            }
            
            .mobile-menu-btn {
                display: block;
            }
            
            .hero-section {
                padding: 80px 0;
            }
            
            .hero-text h1 {
                font-size: 2rem;
            }
            
            .skills-grid {
                grid-template-columns: 1fr;
            }
            
            .portfolio-grid {
                grid-template-columns: 1fr;
            }
            
            .portfolio-filter {
                flex-direction: column;
                align-items: center;
            }
            
            .filter-btn {
                width: 100%;
                max-width: 200px;
            }
        }
        
        @media (max-width: 480px) {
            .hero-text h1 {
                font-size: 1.8rem;
            }
            
            .about-section h2, .portfolio-section h2, .contact-section h2 {
                font-size: 2rem;
            }
            
            .btn {
                padding: 12px 25px;
                font-size: 0.9rem;
            }
            
            .contact-form {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="portfolio-header">
        <div class="container">
            <div class="header-content">
                <div class="portfolio-logo">Portfolio<span>.</span></div>
                <nav class="nav-menu" id="navMenu">
                    <a href="#home" class="nav-link">Home</a>
                    <a href="#about" class="nav-link">About</a>
                    <a href="#portfolio" class="nav-link">Portfolio</a>
                    <a href="#contact" class="nav-link">Contact</a>
                </nav>
                <button class="mobile-menu-btn" id="mobileMenuBtn">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <div class="hero-badge">Hello, I'm a Creative Designer</div>
                    <h1>Bringing Ideas to <span>Life with Design</span></h1>
                    <p>I create beautiful, functional designs that solve problems and delight users. Let's work together to make something amazing.</p>
                    <div class="cta-buttons">
                        <a href="#portfolio" class="btn btn-primary"><i class="fas fa-briefcase"></i> View My Work</a>
                        <a href="#contact" class="btn btn-secondary"><i class="fas fa-envelope"></i> Get In Touch</a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" alt="Portfolio Image">
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Section -->
    <section class="about-section" id="about">
        <div class="container">
            <h2>About Me</h2>
            <div class="about-content">
                <div class="about-text">
                    <p>Hello! I'm a passionate designer with over 5 years of experience creating digital experiences that are both beautiful and functional. My journey in design started with a love for art and technology, and it has evolved into a career focused on user-centered design.</p>
                    <p>I believe that good design should not only look great but also solve real problems and create meaningful connections between users and products.</p>
                    <p>When I'm not designing, you can find me exploring nature, trying new recipes in the kitchen, or reading about the latest design trends and technologies.</p>
                </div>
                <div class="skills">
                    <h3>My Skills</h3>
                    <div class="skills-grid">
                        <div class="skill-item">
                            <h4><i class="fas fa-pencil-alt"></i> UI/UX Design</h4>
                            <p>Creating intuitive and engaging user interfaces</p>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 90%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <h4><i class="fas fa-code"></i> Frontend Development</h4>
                            <p>Bringing designs to life with clean code</p>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 80%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <h4><i class="fas fa-mobile-alt"></i> Responsive Design</h4>
                            <p>Designing for all devices and screen sizes</p>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 95%"></div>
                            </div>
                        </div>
                        <div class="skill-item">
                            <h4><i class="fas fa-palette"></i> Brand Identity</h4>
                            <p>Creating cohesive and memorable brand experiences</p>
                            <div class="skill-bar">
                                <div class="skill-progress" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Portfolio Section -->
    <section class="portfolio-section" id="portfolio">
        <div class="container">
            <h2>My Portfolio</h2>
            <p>Here are some of my recent projects that showcase my design process and capabilities.</p>
            
            <div class="portfolio-filter">
                <button class="filter-btn active" data-filter="all">All</button>
                <button class="filter-btn" data-filter="web">Web Design</button>
                <button class="filter-btn" data-filter="mobile">Mobile Apps</button>
                <button class="filter-btn" data-filter="branding">Branding</button>
            </div>
            
            <div class="portfolio-grid">
                <div class="portfolio-item" data-category="web">
                    <div class="portfolio-image">
                        <img src="https://images.unsplash.com/photo-1558655146-9f40138edfeb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80" alt="Project 1">
                        <div class="portfolio-overlay">
                            <div class="portfolio-links">
                                <a href="#" class="portfolio-link"><i class="fas fa-external-link-alt"></i></a>
                                <a href="#" class="portfolio-link"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <h3>E-commerce Website Redesign</h3>
                        <p>A complete redesign of an online store focusing on improving user experience and conversion rates.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Web Design</span>
                            <span class="portfolio-tag">UI/UX</span>
                            <span class="portfolio-tag">E-commerce</span>
                        </div>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="mobile">
                    <div class="portfolio-image">
                        <img src="https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80" alt="Project 2">
                        <div class="portfolio-overlay">
                            <div class="portfolio-links">
                                <a href="#" class="portfolio-link"><i class="fas fa-external-link-alt"></i></a>
                                <a href="#" class="portfolio-link"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <h3>Fitness Tracking App</h3>
                        <p>A mobile application that helps users track their workouts, nutrition, and health goals.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Mobile App</span>
                            <span class="portfolio-tag">UI/UX</span>
                            <span class="portfolio-tag">Health</span>
                        </div>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="branding">
                    <div class="portfolio-image">
                        <img src="https://images.unsplash.com/photo-1561070791-2526d30994b5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80" alt="Project 3">
                        <div class="portfolio-overlay">
                            <div class="portfolio-links">
                                <a href="#" class="portfolio-link"><i class="fas fa-external-link-alt"></i></a>
                                <a href="#" class="portfolio-link"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <h3>Coffee Shop Brand Identity</h3>
                        <p>Complete brand identity design for a local coffee shop including logo, packaging, and marketing materials.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Branding</span>
                            <span class="portfolio-tag">Logo Design</span>
                            <span class="portfolio-tag">Packaging</span>
                        </div>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="web">
                    <div class="portfolio-image">
                        <img src="https://images.unsplash.com/photo-1460925895917-afdab827c52f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1115&q=80" alt="Project 4">
                        <div class="portfolio-overlay">
                            <div class="portfolio-links">
                                <a href="#" class="portfolio-link"><i class="fas fa-external-link-alt"></i></a>
                                <a href="#" class="portfolio-link"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <h3>Financial Dashboard</h3>
                        <p>A comprehensive dashboard for financial data visualization and analysis.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Web Design</span>
                            <span class="portfolio-tag">Dashboard</span>
                            <span class="portfolio-tag">Data Visualization</span>
                        </div>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="mobile">
                    <div class="portfolio-image">
                        <img src="https://images.unsplash.com/photo-1558618666-fcd25c85cd64?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80" alt="Project 5">
                        <div class="portfolio-overlay">
                            <div class="portfolio-links">
                                <a href="#" class="portfolio-link"><i class="fas fa-external-link-alt"></i></a>
                                <a href="#" class="portfolio-link"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <h3>Travel Planning App</h3>
                        <p>An app that helps users plan their trips, book accommodations, and discover local attractions.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Mobile App</span>
                            <span class="portfolio-tag">Travel</span>
                            <span class="portfolio-tag">UI/UX</span>
                        </div>
                    </div>
                </div>
                
                <div class="portfolio-item" data-category="branding">
                    <div class="portfolio-image">
                        <img src="https://images.unsplash.com/photo-1565688534245-05d6b5be184a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" alt="Project 6">
                        <div class="portfolio-overlay">
                            <div class="portfolio-links">
                                <a href="#" class="portfolio-link"><i class="fas fa-external-link-alt"></i></a>
                                <a href="#" class="portfolio-link"><i class="fas fa-search"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="portfolio-info">
                        <h3>Restaurant Branding</h3>
                        <p>Complete brand identity for a fine dining restaurant including menu design and interior branding elements.</p>
                        <div class="portfolio-tags">
                            <span class="portfolio-tag">Branding</span>
                            <span class="portfolio-tag">Restaurant</span>
                            <span class="portfolio-tag">Print Design</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section" id="contact">
        <div class="container">
            <h2>Get In Touch</h2>
            <p>Have a project in mind or want to collaborate? I'd love to hear from you!</p>
            
            <div class="contact-content">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Location</h4>
                            <p>Kuala Lumpur, Malaysia</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Email</h4>
                            <p>hello@portfolio.com</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Phone</h4>
                            <p>+60 12-345 6789</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-share-alt"></i>
                        </div>
                        <div class="contact-details">
                            <h4>Social Media</h4>
                            <div class="social-links">
                                <a href="#" class="social-link"><i class="fab fa-dribbble"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-behance"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form">
                    <form id="contactForm">
                        <div class="form-group">
                            <label for="name">Your Name</label>
                            <input type="text" id="name" placeholder="Enter your name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Your Email</label>
                            <input type="email" id="email" placeholder="Enter your email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" placeholder="Enter the subject" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="message">Your Message</label>
                            <textarea id="message" placeholder="Enter your message" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            <i class="fas fa-paper-plane"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="portfolio-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">Portfolio<span>.</span></div>
                
                <div class="footer-links">
                    <a href="#home" class="footer-link">Home</a>
                    <a href="#about" class="footer-link">About</a>
                    <a href="#portfolio" class="footer-link">Portfolio</a>
                    <a href="#contact" class="footer-link">Contact</a>
                </div>
                
                <div class="footer-social">
                    <a href="#"><i class="fab fa-dribbble"></i></a>
                    <a href="#"><i class="fab fa-behance"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                </div>
                
                <div class="copyright">
                    &copy; 2023 Portfolio. All Rights Reserved.
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile Menu Toggle
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('navMenu').classList.toggle('active');
        });
        
        // Portfolio Filter
        const filterButtons = document.querySelectorAll('.filter-btn');
        const portfolioItems = document.querySelectorAll('.portfolio-item');
        
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                
                // Add active class to clicked button
                this.classList.add('active');
                
                const filterValue = this.getAttribute('data-filter');
                
                portfolioItems.forEach(item => {
                    if (filterValue === 'all' || item.getAttribute('data-category') === filterValue) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
        
        // Smooth Scrolling for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if(targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if(targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                    
                    // Close mobile menu if open
                    document.getElementById('navMenu').classList.remove('active');
                }
            });
        });
        
        // Contact Form Submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form values
            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const subject = document.getElementById('subject').value;
            const message = document.getElementById('message').value;
            
            // In a real application, you would send this data to a server
            // For this example, we'll just show an alert
            alert(`Thank you, ${name}! Your message has been sent. I'll get back to you soon.`);
            
            // Reset form
            this.reset();
        });
        
        // Add scroll effect to header
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.portfolio-header');
            if (window.scrollY > 100) {
                header.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
            } else {
                header.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            }
        });
    </script>
</body>
</html>