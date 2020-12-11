<!DOCTYPE html>
<html lang="hu-HU">
	<head>
		<title>Osztálykarácsony 2020</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	</head>
	<body>
		<div style="margin:auto; width:240px; border: 2px solid red; padding: 10px;">
			<form style="width:300px" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				Monogramm caps lockkal  <input type="text" name="name" autofocus>
				<input type="submit">
			</form>
		</div>
		<?php
			if ($_SERVER['REQUEST_METHOD'] == "POST") {

				echo '<div style="margin:auto; width:240px; border: 2px solid red; padding: 10px;">';

				$conn = new mysqli("localhost", "debian-sys-maint", "30BO5y0nrmvdQDA3", "kari");

				$name = $_POST["name"];													// Az illető saját neve

				$tries = 0;
				while ($tries < 35) {

					$sql = 'SELECT Pár FROM párok WHERE Név="' . $name . '"';							// Keresés párra
					$result = $conn -> query($sql);
					$row = $result -> fetch_assoc();

					if ($row["Pár"] == "") {											// Van-e párod?

						$sql = 'SELECT Név FROM párok WHERE Pár="' . $name . '"';						// Keresés hogy már pár vagy-e
						$result = $conn ->query($sql);
						$row = $result -> fetch_assoc();

						if ($row["Név"] == "") {										// Párja vagy-e valakinek?

							$sql = 'SELECT Név FROM párok WHERE Név!="' . $name . '" ORDER BY RAND() LIMIT 1';		// Véletlen név
							$result = $conn -> query($sql);
							$row = $result -> fetch_assoc();
							echo "A lehetséges pár " . $row["Név"] . "<br>";
							$pair = $row["Név"];

							$sql = 'SELECT Pár FROM párok WHERE Név="' . $pair . '"';					// Keresés a véletlen névre párként
							$result = $conn -> query($sql);
							$row = $result -> fetch_assoc();
							echo "A lehetséges pár párja " . $row["Pár"] . "<br>";

							if ($row["Pár"] == "") {									// A véletlen név párja-e egy másik névnek
								echo $pair . "<br>";
								$sql = 'UPDATE párok SET Pár="' . $pair . '" WHERE Név="' . $name . '"';		// Pár elmentése
								$result = $conn -> query($sql);
							}
						} else {
							echo "Te már " . $row["Név"] . " párja vagy!";							// Valakinek már a párja vagy

							$sql = 'UPDATE párok SET Pár="' . $row["Név"] . '" WHERE Név="' . $name . '"';			// Pár elmentése
							$result = $conn -> query($sql);
						}
						$tries++;

					} else {
						echo "A te párod " . $row["Pár"] . "<br>";								// Már van párod
						$tries = 99;
						break;
					}
				}
			}
			echo "</div>";
		?>
	</body>
</html>
