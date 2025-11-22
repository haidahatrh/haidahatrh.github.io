<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Haidah Athirah - Portfolio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Header Styles */
        header {
            background: linear-gradient(135deg, #1a2a6c, #2a3a7c);
            color: white;
            padding: 2.5rem 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .profile-header h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .contact-info {
            margin: 1rem 0;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .contact-info a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .contact-info a:hover {
            color: #a8d0e6;
            transform: translateY(-2px);
        }

        .location {
            font-style: italic;
            margin-top: 10px;
            opacity: 0.9;
        }

        /* Section Styles */
        section {
            background: white;
            margin: 2rem 0;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        section:hover {
            transform: translateY(-5px);
        }

        h2 {
            color: #1a2a6c;
            border-bottom: 2px solid #1a2a6c;
            padding-bottom: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        h2 i {
            color: #2a3a7c;
        }

        h3 {
            color: #2a3a7c;
            margin-bottom: 0.5rem;
            font-size: 1.3rem;
        }

        /* Profile Section */
        .profile-text {
            font-size: 1.1rem;
            line-height: 1.8;
            text-align: justify;
        }

        .highlight {
            color: #1a2a6c;
            font-weight: 600;
        }

        /* Education & Experience */
        .timeline-item {
            margin-bottom: 2rem;
            padding-left: 1.5rem;
            border-left: 3px solid #2a3a7c;
            position: relative;
        }

        .timeline-item:last-child {
            margin-bottom: 0;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #1a2a6c;
            left: -7.5px;
            top: 5px;
        }

        .timeline-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            margin-bottom: 0.5rem;
        }

        .date {
            color: #1a2a6c;
            font-weight: bold;
            background: #e9ecef;
            padding: 3px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
        }

        .achievements {
            color: #28a745;
            font-weight: bold;
            margin-top: 0.5rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Projects Section */
        .project-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-top: 1rem;
        }

        .project-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            border-left: 4px solid #2a3a7c;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .project-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
        }

        .project-card h4 {
            color: #1a2a6c;
            margin-bottom: 0.8rem;
            font-size: 1.2rem;
        }

        .tools {
            background: #e9ecef;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.9rem;
            color: #495057;
            margin-top: auto;
            display: inline-block;
            font-weight: 500;
        }

        /* Certifications & Activities */
        .activity-item {
            margin-bottom: 1.5rem;
            padding: 1.2rem;
            background: #f8f9fa;
            border-radius: 8px;
            border-left: 3px solid #2a3a7c;
        }

        .activity-item:last-child {
            margin-bottom: 0;
        }

        /* Skills Section */
        .skills-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .skill-category {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 8px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .skill-category:hover {
            transform: scale(1.03);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .skill-category h4 {
            color: #1a2a6c;
            margin-bottom: 1rem;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .skill-list {
            list-style: none;
        }

        .skill-list li {
            padding: 0.5rem 0;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .skill-list li:last-child {
            border-bottom: none;
        }

        .skill-list i {
            color: #2a3a7c;
        }

        /* Footer */
        footer {
            background: #1a2a6c;
            color: white;
            text-align: center;
            padding: 2rem 0;
            margin-top: 2rem;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .social-links {
            display: flex;
            gap: 20px;
            margin-top: 10px;
        }

        .social-links a {
            color: white;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            color: #a8d0e6;
            transform: translateY(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .profile-header h1 {
                font-size: 2rem;
            }
            
            .timeline-header {
                flex-direction: column;
                gap: 10px;
            }
            
            .project-grid {
                grid-template-columns: 1fr;
            }
            
            .skills-container {
                grid-template-columns: 1fr;
            }
            
            .contact-info {
                flex-direction: column;
                gap: 10px;
            }
            
            h2 {
                font-size: 1.5rem;
            }
        }

        /* Print Styles */
        @media print {
            header {
                background: #1a2a6c !important;
                -webkit-print-color-adjust: exact;
            }
            
            section {
                box-shadow: none;
                margin: 1rem 0;
            }
            
            .contact-info a::after {
                content: " (" attr(href) ")";
            }
        }
    </style>
</head>
<body>
    <!-- Header Section -->
    <header>
        <div class="container">
            <div class="profile-header">
                <h1>HAIDAH ATHIRAH BINTI HAMZAH</h1>
                <div class="contact-info">
                    <a href="tel:+60192152972"><i class="fas fa-phone"></i> (+60) 19-215 2972</a>
                    <a href="mailto:haidahatrh@gmail.com"><i class="fas fa-envelope"></i> haidahatrh@gmail.com</a>
                    <a href="https://www.linkedin.com/in/haidah-athirah-a90526305/" target="_blank"><i class="fab fa-linkedin"></i> LinkedIn Profile</a>
                </div>
                <div class="location"><i class="fas fa-map-marker-alt"></i> Ampang, Selangor, Malaysia</div>
            </div>
        </div>
    </header>

    <div class="container">
        <!-- Profile Section -->
        <section id="profile">
            <h2><i class="fas fa-user"></i> PROFILE</h2>
            <div class="profile-text">
                <p>Bachelor of Computer Science (Hons.) in <span class="highlight">Netcentric Computing</span> student, currently completing an internship until 12th December 2025 and seeking to join the 12-month <span class="highlight">PROTÉGÉ Graduate Trainee</span> program. Skilled in <span class="highlight">analytical thinking, problem-solving, and adapting to new digital tools and processes</span>. Fluent in both Malay and English, possess a positive attitude and am ready to step outside my comfort zone.</p>
            </div>
        </section>

        <!-- Education & Experience Section -->
        <section id="education-experience">
            <h2><i class="fas fa-graduation-cap"></i> EDUCATION & WORK EXPERIENCE</h2>
            
            <div class="timeline-item">
                <div class="timeline-header">
                    <h3>SK Rich Trading Sdn Bhd</h3>
                    <div class="date">2025 - Present</div>
                </div>
                <p><strong>IT department intern</strong></p>
            </div>

            <div class="timeline-item">
                <div class="timeline-header">
                    <h3>Universiti Teknologi MARA (UiTM), Shah Alam</h3>
                    <div class="date">2022 – Present</div>
                </div>
                <p><strong>Bachelor of Computer Science (Hons.) Netcentric Computing</strong></p>
                <div class="achievements"><i class="fas fa-trophy"></i> CGPA: 3.28 | Dean's List Award (Semester 4, 5 & 6)</div>
            </div>

            <div class="timeline-item">
                <div class="timeline-header">
                    <h3>Kolej Matrikulasi Selangor (KMS)</h3>
                    <div class="date">2020 – 2022</div>
                </div>
                <p><strong>Physical Science (Module 2)</strong></p>
                <div class="achievements"><i class="fas fa-award"></i> CGPA: 3.57 | MUET Band 4</div>
            </div>
        </section>

        <!-- Projects Section -->
        <section id="projects">
            <h2><i class="fas fa-code"></i> PROJECTS</h2>
            <div class="project-grid">
                <div class="project-card">
                    <h4>Dental Health Tracker – Mobile App using Reminders & Gamification</h4>
                    <p>Developed an individual final year project to engage parents in their children's dental health.</p>
                    <div class="tools"><i class="fas fa-tools"></i> Tools: Android Studio, Firebase, Flutter</div>
                </div>

                <div class="project-card">
                    <h4>Task Management System</h4>
                    <p>Backend developer for a system integrated with both web and mobile apps.</p>
                    <div class="tools"><i class="fas fa-tools"></i> Tools: Laravel, Flutter, VS Code, VMware</div>
                </div>

                <div class="project-card">
                    <h4>Missing Child Detector Device (IoT)</h4>
                    <p>Team project to build a GPS and GSM-enabled device.</p>
                    <div class="tools"><i class="fas fa-tools"></i> Tools: Arduino, GSM, GPS, Blynk</div>
                </div>

                <div class="project-card">
                    <h4>Homestay Booking Management System</h4>
                    <p>Developed a homestay booking system with web-based integration.</p>
                    <div class="tools"><i class="fas fa-tools"></i> Tools: Microsoft Access, XAMPP, PHP, SQL</div>
                </div>

                <div class="project-card">
                    <h4>VALOOBUY Contractor Hub</h4>
                    <p>Developed an e-commerce web platform specialized for registered contractors to get custom price and enhance affiliate sales.</p>
                    <div class="tools"><i class="fas fa-tools"></i> Tools: Plesk, PHP, Boost.Space, Google Sheets</div>
                </div>
            </div>
        </section>

        <!-- Certifications & Activities Section -->
        <section id="certifications-activities">
            <h2><i class="fas fa-certificate"></i> CERTIFICATIONS & ACTIVITIES</h2>
            
            <div class="activity-item">
                <h4>SULAM@BUDIMAN : Cybersecurity Awareness and Explorace (2023)</h4>
                <p>Assisted in designing and managing cybersecurity-themed explore race challenges to engage participants in an interactive learning experience.</p>
            </div>

            <div class="activity-item">
                <h4>Kursus Pengurusan Organisasi (2024)</h4>
                <p>A 3-day program focused on leadership and event management for UiTM student leaders.</p>
            </div>

            <div class="activity-item">
                <h4>SOKI II 2024 & MyDigital Maker Fair (2024)</h4>
                <p>Volunteer: Managed event opening and closing; promoted entrepreneurship to parents and children.</p>
            </div>

            <div class="activity-item">
                <h4>iHACK Competition (2024)</h4>
                <p>Organizer Team: Oversaw event operations and logistics.</p>
            </div>

            <div class="activity-item">
                <h4>Capture The Flag – Girls in CTF (2023)</h4>
                <p>Competitor: Gained hands-on experience in cybersecurity challenges such as cryptography, web vulnerabilities, and digital forensics.</p>
            </div>
        </section>

        <!-- Skills Section -->
        <section id="skills">
            <h2><i class="fas fa-cogs"></i> SKILLS</h2>
            <div class="skills-container">
                <div class="skill-category">
                    <h4><i class="fas fa-laptop-code"></i> Programming/Development</h4>
                    <ul class="skill-list">
                        <li><i class="fas fa-check"></i> Flutter</li>
                        <li><i class="fas fa-check"></i> Laravel</li>
                        <li><i class="fas fa-check"></i> PHP</li>
                        <li><i class="fas fa-check"></i> SQL</li>
                    </ul>
                </div>

                <div class="skill-category">
                    <h4><i class="fas fa-toolbox"></i> Tools</h4>
                    <ul class="skill-list">
                        <li><i class="fas fa-check"></i> Android Studio</li>
                        <li><i class="fas fa-check"></i> Firebase</li>
                        <li><i class="fas fa-check"></i> XAMPP</li>
                        <li><i class="fas fa-check"></i> VS Code</li>
                        <li><i class="fas fa-check"></i> VMware</li>
                        <li><i class="fas fa-check"></i> Arduino</li>
                    </ul>
                </div>

                <div class="skill-category">
                    <h4><i class="fas fa-language"></i> Languages</h4>
                    <ul class="skill-list">
                        <li><i class="fas fa-check"></i> Malay (Native)</li>
                        <li><i class="fas fa-check"></i> English (Fluent)</li>
                    </ul>
                </div>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2024 Haidah Athirah Binti Hamzah. All Rights Reserved.</p>
                <p>Aspiring Computer Scientist | PROTÉGÉ Graduate Trainee Candidate</p>
                <div class="social-links">
                    <a href="mailto:haidahatrh@gmail.com"><i class="fas fa-envelope"></i></a>
                    <a href="https://www.linkedin.com/in/haidah-athirah-a90526305/" target="_blank"><i class="fab fa-linkedin"></i></a>
                    <a href="tel:+60192152972"><i class="fas fa-phone"></i></a>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
