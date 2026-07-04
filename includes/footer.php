<?php
// ============================================================
// ChamaFunds – includes/footer.php
// Shared public footer
// ============================================================
?>
<footer class="footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
          <div style="width:36px;height:36px;background:#FF6B4A;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:800;font-size:.75rem;">CF</div>
          <span style="color:#fff;font-weight:800;">ChamaFunds</span>
        </div>
        <p>Pool money together for anything that matters.</p>
        <div class="footer-socials">
          <a href="#" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
          <a href="#" aria-label="Facebook"><i class="fab fa-facebook"></i></a>
          <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
          <a href="#" aria-label="LinkedIn"><i class="fab fa-linkedin"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Platform</h4>
        <ul>
          <li><a href="<?= BASE ?>/campaign-drives.php">Campaign Drives</a></li>
          <li><a href="<?= BASE ?>/donate.php">Donate</a></li>
          <li><a href="<?= BASE ?>/create-campaign.php">Start a Campaign</a></li>
          <li><a href="<?= BASE ?>/index.php#how-it-works">How It Works</a></li>
          <li><a href="<?= BASE ?>/index.php#faq">FAQ</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Company</h4>
        <ul>
          <li><a href="#">About</a></li>
          <li><a href="#">Blog</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Careers</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Legal</h4>
        <ul>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Terms of Service</a></li>
          <li><a href="#">Cookie Policy</a></li>
          <li><a href="#">Refund Policy</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© <?= date('Y') ?> ChamaFunds. Made with ❤️ by Kakebe Technologies</span>
      <div class="footer-legal">
        <a href="#">Privacy</a>
        <a href="#">Terms</a>
        <a href="#">Cookies</a>
      </div>
    </div>
  </div>
</footer>
<script src="<?= BASE ?>/js/main.js"></script>
<?php if (!empty($extraJs)) echo $extraJs; ?>
</body>
</html>
