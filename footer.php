<footer class="site-footer">
            <div class="footer-container">
                <div class="footer-content">
                    <div class="footer-logo-col">
                        <a href="#" class="nav-logo"></a>
                    </div>
                    
                    <div class="footer-col">
                        <h4>Menu</h4>
                        <ul>
                            <li><a href="#home">Home</a></li>
                            <li><a href="#about">About</a></li>
                            <li><a href="#skills">Skills</a></li>
                            <li><a href="#projects">Projects</a></li>
                        </ul>
                    </div>
                    
                    <div class="footer-col">
                        <h4>Connect</h4>
                        <ul>
                            <?php
                                // DYNAMIC CONTENT: Fetch footer social links
                                $sql = "SELECT * FROM social_links WHERE location = 'footer' ORDER BY id";
                                $result = $conn->query($sql);
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo '<li><a href="' . htmlspecialchars($row['url']) . '" target="_blank">' . htmlspecialchars($row['name']) . '</a></li>';
                                    }
                                }
                            ?>
                        </ul>
                    </div>
                    
                    <div class="footer-copyright">
                        <p>&copy; 2025 Shashank Vakalapudi. All rights reserved.</p>
                        <div class="footer-legal-links">
                            <a href="#">Privacy Policy</a>
                            <a href="#">Terms of Service</a>
                        </div>
                    </div>
                </div>
                <hr class="footer-divider">
            </div>
        </footer>
    
    </div> <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles-shape-emoji@2.12.0/tsparticles.shape.emoji.min.js"></script>
    
    <script src="script.js"></script>

    <?php
        // Close the database connection at the end of the page
        $conn->close();
    ?>
</body>
</html>