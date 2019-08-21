<?php
if (preg_match('imagini+/[a-zA-Z0-9\/\-_\(\)]', "imagini/!", $matches)) {
  echo "Match was found <br />";
  echo $matches[0];
}
?>