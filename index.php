<?php

date_default_timezone_set('UTC');
if (isset($_POST['procedure']) && isset($_POST['ip'])) {
	setcookie("package",isset($_POST['package'])?trim($_POST['package']):' ');
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ip'])) {
    $novo_ip = trim($_POST['ip']); // IP digitado pelo usuário
    $ip_atual = isset($_COOKIE['ip']) ? $_COOKIE['ip'] : '';

    // Verifica se o IP é diferente
    if ($novo_ip !== $ip_atual) {
        setcookie('ip', $novo_ip, time() + (365 * 24 * 60 * 60), '/');
        header('Location: ' . $_SERVER['PHP_SELF']); 
        exit;
    }
}
	setcookie("task_id",intval(isset($_POST['task_id'])?trim($_POST['task_id']):' '));}
	
	///Settings
	$folder = "./pkg/";  		// Folder with Games, '.' folder where index.php, last simbol must be '/'
	$folder_log = './extra/';  	// Folder with logs,  '.' folder where index.php, last simbol must be '/'
	$lang = "pt";				// Select language, for english set "eng"
	$temp_folder = "./extra/temp";
	
	// Number of games per page
	$games_per_page = 10;

	// Get the current page or set to 1 if not provided
	$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
	$start = ($page - 1) * $games_per_page;
	?>		
<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title>PS4 Remote Package Installer GUI</title>
		<link href="imagens/favicon.png" rel="shortcut icon" type="image/x-icon">
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
		<script type="text/javascript">
			function WhereYouWillSend(a){document.getElementById('url').value=a;document.getElementById('url').size=50;};
		</script>
		
		<script>
        // Funções para abrir e fechar o menu
        function openNav() {
            document.getElementById("mySidebar").style.width = "280px";
            document.getElementById("main").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft = "0";
        }
    </script>

	</head>
	<body>
		<div class= "content-title">
			<button title="Configuração" class="openbtn" onclick="openNav()"><i class="fa-solid fa-bars" style="font-size: xx-large;padding: 0;color: white;transform: none;position: initial;background-color: transparent;"></i></button>
			<center>
				<h1>PS4 Remote Package Installer GUI</h1>
			</center>
			<form action="index.php" method="GET" style="text-align:center;">
				<label for="search">Buscar pacote:</label>
				<input type="text" id="search" name="search" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style=" margin-bottom: 10px;padding: 8px;margin-top: 10px;border-radius: 4px;">
				<input type="submit" id="buscar" value="Buscar" style="display:none;">
				<button class="btn-touch"><label for="buscar"><i title="Buscar" style=" padding-bottom: 11px;font-size: 15px;margin-left: 16px;position: initial;" class="fa-solid fa-magnifying-glass"></i></label></button>
			</form>
		</div>
				<!-- Menu lateral -->
		<div id="mySidebar" class="sidebar">
			<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
			<form action="index.php" method="POST">
				<label for="ip" style="color: white;">IP PS4</label>
				<input type="text" id="ip" name="ip" required pattern="^([0-9]{1,3}\.){3}[0-9]{1,3}$" value="<?php echo isset($_COOKIE['ip']) ? $_COOKIE['ip'] : ''; ?>" style="display: block; margin: auto; padding: 8px; margin-top: 10px;border-radius: 4px;" />
				<input title="Salvar" class="save-ip" type="submit" value="Salvar" style="margin-top: 10px;" />
				<input style="display:none" type="text" id="url" size="" name="package" value="http://"/>
		</div>
				
		<div class="package-container">
			<?php
				// Call the function to display only 10 games per page
				$search = isset($_GET['search']) ? $_GET['search'] : ''; // Get the search term from the query string
				allDir($folder, $start, $games_per_page, $search); // Pass the search term to the function
			?>
		</div>

				<script>
				// Função para tratar o clique no botão "Download"
				function selectPackage(url) {
					// Aqui você pode adicionar lógica para armazenar o URL do pacote selecionado
					document.getElementById('url').value = url;
					alert('Pacote selecionado: ' + url); // Exibe uma mensagem ou faça outra ação
				}
				</script>

				<input type="hidden" name="procedure" value="install">
			</form>
	
		<?php
			$package = isset($_POST['package'])?trim($_POST['package']):' ';
			$ip = isset($_POST['ip'])?trim($_POST['ip']):' ';
			$procedure = isset($_POST['procedure'])?trim($_POST['procedure']):' ';
			$task_id = intval(isset($_POST['task_id'])?trim($_POST['task_id']):' ');
			
			if (isset($_POST['procedure']) && isset($_POST['ip'])) {
		
				if(strpos($package, ' ') !== false) {
					if(!file_exists($temp_folder)) mkdir($temp_folder, 0777, true);
				    $path = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
				    $fold = substr($path, 0, -strlen(basename($path))-1); 
					$package_link = substr($package, strlen($fold));
					$link = $temp_folder."/".basename(str_replace(" ","",$package_link));
					$pa = dirname(__FILE__).'/'.substr($package_link, 1);
					if (is_link($link)) unlink($link);
					symlink($pa, $link);
					$package = $fold.substr($temp_folder, 1)."/".basename(str_replace(" ","",$package_link));
				}
		
			$type = 'direct';
			$packages[] = $package;
			
			if ($procedure == 'install') {$data = array("type" => $type, "packages" => $packages);} 
			if ($procedure == 'get_task_progress') {$data = array("task_id" => $task_id);}                                                                
			$data_string = json_encode($data);                                                                                   
			                                                                                                                    
			$ch = curl_init("http://$ip:12801/api/$procedure");                                                                      
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);                                                                      
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
			    'Content-Type: application/json',                                                                                 
			    'Content-Length: ' . strlen($data_string))                                                                       
			);                                                                                                                   
			                                                                                                                 
			$result = curl_exec($ch);
			
			curl_close($ch);
			
			if ($result == TRUE) {
			
			$status = parse("status",$result);
			
			if ($status == 'fail') {$error = parse("error",$result); $error_r = parse("error_code",$result); echo lang_echo($lang, "something_wrong", $error, $error_r,0,0,0,0);}
			if ($status == 'success' && $procedure == 'install') {
				$task_id = parse("task_id",$result); $title = parse("title",$result); 
				echo lang_echo($lang, "install_start", $title, $task_id,0,0,0,0);		
				write_file($folder_log."task_id.txt","\n".json_encode(array($task_id,$title)));	
			}
			
			if ($status == 'success' && $procedure == 'get_task_progress') {
			//		$length = round((hexdec(parse("length",$result)))/1000000000, 3);
			//		$transferred = round((hexdec(parse("transferred",$result)))/1000000000, 3);
				$length_total = round((hexdec(parse("length_total",$result)))/1000000000, 3);
				$transferred_total = round((hexdec(parse("transferred_total",$result)))/1000000000, 3);
				$num_index = parse("num_index",$result);
				$num_total = parse("num_total",$result);
			//		$rest_sec = parse("rest_sec",$result);
				$rest_sec_total = round(parse("rest_sec_total",$result)/60, 1);
				$preparing_percent = parse("preparing_percent",$result);
			//		$local_copy_percent = parse("local_copy_percent",$result);
				$error = parse("error",$result);
			//		$bits = (hexdec(parse("bits",$result))); 
				$percents = round($transferred_total * 100 / $length_total); 
				if ($error == 0 && $transferred_total == $length_total) { echo lang_echo($lang, "install_success",0,0,0,0);} else
				echo lang_echo($lang, "status_install",$num_index,$num_total,$rest_sec_total,$percents,$transferred_total,$length_total);
			}
			write_file($folder_log."logs.txt","\n".date(DATE_RFC822)."		$procedure		$result");			//write logs
			} else echo lang_echo($lang, "curl_error",0,0,0,0,0,0);
			
			} else echo lang_echo($lang, "empty_all",0,0,0,0,0,0);
			
			echo "<br><br>". last_install($folder_log);
			?>
		</p>
	</body>
	   <script>
		function toggleExpand(containerId) {
			var container = document.getElementById(containerId);
			
			// Alterna a visibilidade do conteúdo da pasta
			container.classList.toggle('expanded'); 

			// Alterna o título entre "Expandir" e "Ocultar"
			if (container.classList.contains('expanded')) {
				container.setAttribute("title", "Ocultar");
			} else {
				container.setAttribute("title", "Expandir");
			}
		}
    </script>

<?php
	// Functions
	
function allDir($dir, $start, $limit, $search = '') {
    $folders = scandir($dir);
    unset($folders[0], $folders[1]); // Remove "." and ".."

    // If there's a search term, filter the folders
    if ($search) {
        $folders = array_filter($folders, function($folder) use ($search) {
            return stripos($folder, $search) !== false;
        });
    }

    // Slice the folders array to get only the ones for the current page
    $folders = array_slice($folders, $start, $limit);

    foreach ($folders as $folder) {
    $folderPath = $dir . "/" . $folder;

    // Check if it's a directory
    if (is_dir($folderPath)) {
		
        echo '<div class="game-container" id="game-container-' . $folder . '" title="Expandir" onclick="toggleExpand(\'game-container-' . $folder . '\', this)" >';
        // Check if there's an image corresponding to the folder name
        $imagePath = $folderPath . '/' . $folder; // Base path for image
        if (file_exists($imagePath . '.png')) {
            $imageUrl = $imagePath . '.png';
        } elseif (file_exists($imagePath . '.jpg')) {
            $imageUrl = $imagePath . '.jpg';
        } else {
            // Default image if none is found
            $imageUrl = './imagens/default.png';
        }

        // Display the image and folder name
        echo '<h2>' . htmlspecialchars($folder) . '</h2>';
		echo '<button class="btn-title"></button>';
        echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($folder) . '" style="max-width: 100%; height: auto;">';

        // Get the files inside the folder
        $files = scandir($folderPath);
        unset($files[0], $files[1]); // Remove "." and ".."

        foreach ($files as $file) {
            $filePath = $folderPath . "/" . $file;

            // Check if it's a file and a valid package
            if (is_file($filePath) && strpos($file, '.pkg') !== false) {
                // Correct the file path to generate the correct link
                $fileUrl = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/' . ltrim($filePath, './');
                echo '<div class="file-item">';
                echo '<p>' . htmlspecialchars($file) . '</p>'; // Display the file name
                echo '<button title="Download" onclick="selectPackage(\'' . $fileUrl . '\')">Download</button>'; // Selection button
                echo '</div>';
            }
        }
		echo '</div>';
    }
}
}

	$files = array();
	$oldFold = '';
	
		function last_install($folder_log){
	  	  if (file_exists($folder_log."task_id.txt")){
	    	  $f = fopen ($folder_log."task_id.txt", "r"); 
	    while (($s = fgets($f)) !== false)
	          $last_line = $s;
		$last = json_decode($last_line);
	   }}
	  
	   function parse($arg,$string){
	   if(preg_match("/\"$arg\": (.*?),/",str_replace('}', ",", $string),$matches)){ $text = str_replace('"', "", $matches[1]);} else {$text = '';}
	   return $text;
	   }
	  
	   function write_file($file, $text){
	     	$filelog = fopen($file,"a+"); 
	     	fwrite($filelog,$text); 
	     	fclose($filelog);
	   }
	  
	   function lang_echo($lang, $text, $arg1, $arg2, $arg3, $arg4, $arg5, $arg6) {
		
		if ($text == 'curl_error' && $lang == 'pt'){
			echo "<script>alert('RPI OpenOrbis Port Não Encontrado');</script>";
			return;
		}
	
		if ($text == 'something_wrong' && $lang == 'pt'){
			echo "<script>alert('Algo deu errado: .$arg1.$arg2');</script>";
			return; 
		}

		if ($text == 'install_start' && $lang == 'pt') {
			echo "<script>alert('Sucesso, jogo: $arg1 está sendo instalado, task_id: $arg2');</script>";

		}
		
	}
	
	 // Calculate the total number of pages
        $total_games = count(scandir($folder)) - 2; // Subtracting . and ..
        $total_pages = ceil($total_games / $games_per_page);

        // Pagination controls
        echo '<div class="pagination" style="text-align:center">';
        if ($page > 1) {
            echo '<a title="Anterior" style="padding-right: 2px;" href="?page=' . ($page - 1) . '"><i class="fa-solid fa-angles-left" style="position: initial;"></i></a>';
        }
        echo ' Página ' . $page . ' de ' . $total_pages . ' ';
        if ($page < $total_pages) {
            echo '<a title="Proxima" style="padding-left: 30px;" href="?page=' . ($page + 1) . '"><i class="fa-solid fa-angles-right" style="position: initial;"></i></a>';
        }
        echo '</div>';

	
	?>
	<footer style="text-align:center;">
		<ul style=list-style:none;padding-left:0>
			<h3 style="margin-bottom: 0">Developers</h3>
			<li><a href="https://github.com/flatz" target="_blank">Remote Package Installer - <b>flatz</b></a></li>
			<li><a href="https://github.com/Backporter" target="_blank">Ps4 Remote Pkg Installer OOSDK - <b>Backporter</b></a></li>
			<li><a href="https://github.com/Sc0rpion" target="_blank">Web Gui - <b>Sc0rpion</b></a></li>
			<li><a href="https://github.com/hosttools1001" target="_blank">Web Gui Update - <b>Hosttools1001</b></a></li>
		</ul>
	</footer>
</html>
