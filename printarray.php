<?php
include 'functions.php';

		echo "<form method='post' action='searchuser.php'>";
		echo "<br>";
		echo "Search user<br>";
		echo "<input type='text' name='search_user' placeholder='Enter username...' value='pewdiepie'><br>";
		echo "<input type='text' name='search_result' placeholder='Enter results count...' value='2'>";
		echo "Search results to show (number)<br>";
		echo "<input type='submit' name='submit' value='Search'>";
		echo "</form>";

				showTags('pewdiepie');

?>