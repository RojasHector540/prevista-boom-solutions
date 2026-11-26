<?php
$logoSizeClass = $logoSizeClass ?? 'w-20 h-20';
$logoAlt       = $logoAlt ?? 'Logo PREVISTA - BOOM SOLUTIONS';
?>
<img src="<?php echo APP_URL; ?>app/views/assets/img/logo-prevista.png"
     alt="<?php echo htmlspecialchars($logoAlt, ENT_QUOTES, 'UTF-8'); ?>"
     class="<?php echo $logoSizeClass; ?> object-contain rounded-lg select-none">