<footer class="footer">
  <div class=" container-fluid ">
    <nav>
      <ul>
        <li>
          <a href="mailto:contato@<?= parse_url(SITE_URL, PHP_URL_HOST); ?>">
            Contato
          </a>
        </li>
        <li>
          <a href="<?= SITE_URL; ?>">
            Pagina Inicial
          </a>
        </li>
      </ul>
    </nav>
    <div class="copyright" id="copyright">
      &copy; <script>
        document.getElementById('copyright').appendChild(document.createTextNode(new Date().getFullYear()))
      </script> by <a href="<?= SITE_URL; ?>" class="text-success" target="_blank"><?= parse_url(SITE_URL, PHP_URL_HOST); ?></a>.
    </div>
  </div>
</footer>
</div>
</div>
<!--   Core JS Files   -->
<script src="<?= SITE_URL; ?>/panel/assets/js/core/jquery.min.js"></script>
<script src="<?= SITE_URL; ?>/panel/assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js" ></script>

<!-- Chart JS -->
<script src="<?= SITE_URL; ?>/panel/assets/js/plugins/chartjs.min.js"></script>
<!--  Notifications Plugin    -->
<script src="<?= SITE_URL; ?>/panel/assets/js/plugins/bootstrap-notify.js?v=2"></script>

<!-- Control Center for Now Ui Dashboard: parallax effects, scripts for the example pages etc -->
<script src="<?= SITE_URL; ?>/panel/assets/js/now-ui-dashboard.js?v=<?= filemtime('assets/js/now-ui-dashboard.js'); ?>" type="text/javascript"></script><!-- Now Ui Dashboard DEMO methods, don't include it in your project! -->
<script src="<?= SITE_URL; ?>/panel/assets/demo/demo.js"></script>

<script src="<?= SITE_URL; ?>/panel/assets/js/jquery.maskMoney.js"></script>
<script src="<?= SITE_URL; ?>/panel/assets/js/jquery.mask.js"></script>

<script type="text/javascript" src="https://ichord.github.io/Caret.js/src/jquery.caret.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/at.js/1.4.1/js/jquery.atwho.min.js" ></script>
<script src="<?= SITE_URL; ?>/panel/assets/js/function.js?v=<?= filemtime('assets/js/function.js'); ?>"></script>
<script src="<?= SITE_URL; ?>/panel/assets/js/sidebar.js?v=<?= filemtime('assets/js/sidebar.js'); ?>"></script>


<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"></script>


<script src="<?= SITE_URL; ?>/panel/assets/js/core/popper.min.js"></script>
<script src="<?= SITE_URL; ?>/panel/assets/js/core/bootstrap.min.js?v=3"></script>

<script src="<?= SITE_URL; ?>/panel/assets/js/plugins/intlTelInput/js/intlTelInput.js"></script>
<script src="<?= SITE_URL; ?>/panel/assets/js/plugins/intlTelInput/js/utils.js"></script>


</body>

</html>
