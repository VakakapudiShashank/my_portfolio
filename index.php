<?php include('header.php'); ?>

    <div id="main-content">

        <main class="hero" id="home">
            <section class="hero-info">
                <p class="intro-text">Hello, I'm</p>
                <h1 class="name-title"><?php echo htmlspecialchars($info['name_title']); ?></h1>
                <h2 class="subtitle"><?php echo htmlspecialchars($info['subtitle']); ?></h2>
                <p class="bio"><?php echo htmlspecialchars($info['bio']); ?></p>

                <div class="button-group">
                    <a href="#projects" class="btn btn-primary">View Projects</a>
                    <a href="#contact" class="btn btn-secondary">Contact Me</a>
                </div>

                <div class="social-links">
                    <?php
                        // DYNAMIC CONTENT: Fetch hero social links
                        $sql = "SELECT * FROM social_links WHERE location = 'hero' ORDER BY id";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<a href="' . htmlspecialchars($row['url']) . '" class="social-icon" aria-label="' . htmlspecialchars($row['name']) . '" target="_blank"><i class="' . htmlspecialchars($row['icon_class']) . '"></i></a>';
                            }
                        }
                    ?>
                </div>
            </section>

            <section class="hero-terminal">
                <div class="terminal-window">
                    <div class="terminal-header">
                        <div class="dots">
                            <span class="dot red"></span>
                            <span class="dot yellow"></span>
                            <span class="dot green"></span>
                        </div>
                        <span class="terminal-title">shashank@portfolio</span>
                    </div>
                    <div class="terminal-body">
                        <p><span class="prompt">$</span> <span class="type-line-1"></span></p>
                        <p><span class="type-line-2"></span></p>
                        <p><span class="type-line-3"></span><span class="cursor"> █</span></p>
                    </div>
                </div>
            </section>
        </main>

        <section class="about-section reveal" id="about">
            <div class="about-content">
                <h2 class="section-title">About Me<span></span></h2>
                <p class="about-intro"><?php echo htmlspecialchars($info['about_intro']); ?></p>
                <p class="about-description"><?php echo htmlspecialchars($info['about_description']); ?></p>
                
                <a href="RESUME.pdf" class="btn btn-cv" download>
                    Download CV <i class="fas fa-download"></i>
                </a>
            </div>
            
            <div class="about-image">
                <div class="about-image-container">
                    <img src="BG.png?t=<?php echo time(); ?>" alt="Shashank Vakalapudi - professional photo">
                </div>
            </div>
        </section>


        <section class="skills-section reveal" id="skills">
            <h2 class="section-title">skills.</h2>
            <div class="skills-grid">
                
                <?php
                    // DYNAMIC CONTENT: Fetch all skills and group them by category name
                    $all_skills = [];
                    $skills_sql = "SELECT s.skill_name, s.icon_class, c.category_name 
                                   FROM skills s 
                                   JOIN skill_categories c ON s.category_id = c.id 
                                   ORDER BY c.category_name, s.id";
                    
                    $skills_result = $conn->query($skills_sql);
                    
                    if ($skills_result && $skills_result->num_rows > 0) {
                        while($skill = $skills_result->fetch_assoc()) {
                            $all_skills[$skill['category_name']][] = $skill;
                        }
                    }

                    // --- IMPORTANT ---
                    // This array controls your layout. 
                    // When you add a new category in the admin panel (e.g., "DevOps"),
                    // you MUST add its name to this array for it to show up.
                    $skill_rows = [
                        'row-3' => ['Programming Languages', 'Frontend Development', 'Backend & Databases'],
                        'row-2' => ['Cybersecurity & IoT', 'Cloud & Developer Tools']
                        // e.g., 'row-2' => ['Cybersecurity & IoT', 'Cloud & Developer Tools', 'DevOps']
                    ];
                    // --- END OF IMPORTANT BLOCK ---
                ?>
                
                <div class="skills-row-3">
                    <?php foreach ($skill_rows['row-3'] as $category): ?>
                        <div class="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front"><h3><?php echo htmlspecialchars($category); ?></h3></div>
                                <div class="flip-card-back">
                                    <ul>
                                        <?php if(isset($all_skills[$category])): ?>
                                            <?php foreach($all_skills[$category] as $skill): ?>
                                                <li><i class="<?php echo htmlspecialchars($skill['icon_class']); ?>"></i> <?php echo htmlspecialchars($skill['skill_name']); ?></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>Skills coming soon...</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div> 
                <div class="skills-row-2">
                    <?php foreach ($skill_rows['row-2'] as $category): ?>
                        <div class="flip-card">
                            <div class="flip-card-inner">
                                <div class="flip-card-front"><h3><?php echo htmlspecialchars($category); ?></h3></div>
                                <div class="flip-card-back">
                                    <ul>
                                        <?php if(isset($all_skills[$category])): ?>
                                            <?php foreach($all_skills[$category] as $skill): ?>
                                                <li><i class="<?php echo htmlspecialchars($skill['icon_class']); ?>"></i> <?php echo htmlspecialchars($skill['skill_name']); ?></li>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <li>Skills coming soon...</li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div> 
            </div> 
        </section> 
        
        <section class="project-section" id="projects">
            <h2 class="section-title">projects.</h2>
            
            <div class="gallery">
                <ul class="cards">
                    
                    <?php
                        // DYNAMIC CONTENT: Fetch all projects
                        $sql = "SELECT * FROM projects ORDER BY id";
                        $result = $conn->query($sql);

                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                    ?>
                        <li>
                            <div class="project-content">
                                <div class="card-image" style="background-image: url('<?php echo htmlspecialchars($row['image_url']); ?>');"></div>
                                <div class="card-info">
                                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                                    <p><?php echo htmlspecialchars($row['description']); ?></p>
                                    
                                    <div class="project-links">
                                        <a href="<?php echo htmlspecialchars($row['live_link']); ?>" target="_blank">Live Demo <i class="fas fa-external-link-alt"></i></a>
                                        <a href="<?php echo htmlspecialchars($row['github_link']); ?>" target="_blank">GitHub <i class="fab fa-github"></i></a>
                                    </div>
                                ins
                            </div>
                        </li>
                    <?php
                            } // End while loop
                        } else {
                            echo "<li><div class='project-content'><div class='card-info'><h3>No projects found</h3></div></div></li>";
                        }
                    ?>
                    
                </ul>
                
                <div class="actions">
                    <button class="prev">Prev</button>
                    <button class="next">Next</button>
                </div>
            </div>
        </section>
        
        <section class="contact-section" id="contact">
            <div class="contact-left">
                <p class="contact-subheading">
                    <span class="dot"></span> Let's Connect
                </p>
                <h2 class="contact-heading">Get in Touch</h2>
                <p class="contact-description"><?php echo htmlspecialchars($info['contact_description']); ?></p>
                
                <div class="contact-social-links">
                    <?php
                        // DYNAMIC CONTENT: Fetch contact social links
                        $sql = "SELECT * FROM social_links WHERE location = 'contact' ORDER BY id";
                        $result = $conn->query($sql);
                        if ($result && $result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<a href="' . htmlspecialchars($row['url']) . '" class="contact-social-icon" aria-label="' . htmlspecialchars($row['name']) . '" target="_blank"><i class="' . htmlspecialchars($row['icon_class']) . '"></i></a>';
                            }
                        }
                    ?>
                </div>
            </div>
            
            <div class="contact-right">
                <form class="contact-form" action="https://formsubmit.co/vakalapudishashank19@gmail.com" method="POST">
                    
                    <div class="form-group">
                        <i class="fas fa-user form-icon"></i>
                        <input type="text" id="name" name="name" placeholder=" " required>
                        <label for="name" class="input-label">Your Name</label>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-envelope form-icon"></i>
                        <input type="email" id="email" name="email" placeholder=" " required>
                        <label for="email" class="input-label">Email Address</label>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-tag form-icon"></i>
                        <input type="text" id="subject" name="subject" placeholder=" " required>
                        <label for="subject" class="input-label">Subject</label>
                    </div>
                    <div class="form-group">
                        <i class="fas fa-pen form-icon-textarea"></i>
                        <textarea id="message" name="message" rows="5" placeholder=" " required></textarea>
                        <label for="message" class="input-label">Your Message</label>
                    </div>
                    <button type="submit" class="send-message-btn">
                        Send Message <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </section>

<?php include('footer.php'); ?>