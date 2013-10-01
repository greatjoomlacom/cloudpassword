<?php
$year_created = 2013;
$date = new DateTime();
$year = (int)$date->format('Y');
?>
<a href="http://cloudpassword.greatjoomla.com/" class="no-decoration" target="_blank">Cloud Password
@if($year_created < $year)
    {{ $year_created }} - {{ $year }}
@else
    {{ $year_created }}
@endif
</a>