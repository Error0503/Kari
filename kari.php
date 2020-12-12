<!DOCTYPE html>
<html lang="hu-HU" style="background-color: #004504">
	<head>
		<title>Osztálykarácsony 2020</title>
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>
	</head>
	<body>
		<div style="text-align:center; margin:auto; width:55vw; border: 2px solid red; border-radius: 5px; padding: 10px; background-color: #004504; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<p>Szia! Ez az idei osztálykarácsony sorsoló oldala. Ahhoz, hogy megtudd, ki a párod, kérlek írd be a monogramod csupa nagybetűvel, és nyomd meg a "Küldés" gombot. Minden neved kezdőKARAKTERE kell, tehát ha Gipsz Zsuzsa Jakab vagy, akkor a monogramod "GZJ".
			<form style="width:50vw; margin-left: 2vw;" method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
				<input type="text" name="name" style="width:40vw; padding: 12px 20px; border: 1px solid red; border-radius:3px;" placeholder="Monogram csupa nagybetűvel" autofocus>
				<br>
				<input style="color: white; font-size: 20px; margin: 5px; padding: 6px 20px; border: 2px solid red; border-radius: 3px; background-color: red;" type="submit">
			</form>
			<?php
				if ($_SERVER['REQUEST_METHOD'] == "POST") {

					echo '<audio autoplay="true" style="display:none;">
						<source src="Rick Roll.mp3" type="audio/wav">
						</audio>';

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
								$pair = $row["Név"];

								$sql = 'SELECT Pár FROM párok WHERE Név="' . $pair . '"';					// Keresés a véletlen névre párként
								$result = $conn -> query($sql);
								$row = $result -> fetch_assoc();

								if ($row["Pár"] == "") {									// A véletlen név párja-e egy másik névnek
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
			?>
		</div>
	</body>
</html>
