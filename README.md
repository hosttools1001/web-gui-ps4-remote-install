Este projeto é uma atualização do ps4 remote package installer gui web do sc0rpion.

use o XAMPP para servi-lo via pc
Baixe e instale o xampp
[XAMPP](https://www.apachefriends.org/pt_br/index.html)

estrutura de pastas
/ps4 remote gui web/
│── /css/                   # Arquivos de estilos CSS
│   ├── style.css
│── /extra/                 # Pasta para logs e arquivos temporários
│   ├── logs.txt
│   ├── task_id.txt
│   ├── /temp/              # Subpasta para arquivos temporários
│── /imagens/               # Armazena imagens usadas no site
│   ├── favicon.png
│   ├── default.png
│── /pkg/                   # Pasta onde os pacotes de instalação são armazenados
│   ├── NomeDoJogo1/        # Cada jogo tem sua própria pasta
│   │   ├── NomeDoJogo1.pkg
│   │   ├── NomeDoJogo1.png  # Capa do jogo (opcional)
│── index.php

Certifique-se que cada .pkg tenha seu próprio diretório dentro do diretório "pkg" a imagem para o .pkg devera ter o mesmo nome do diretório na qual ela está.
E possível ter mais de um .pkg por diretório  

No PS4 Execute o ps4_remote_pkg_installer-OOSDK
[remote pkg](https://github.com/Backporter/ps4_remote_pkg_installer-OOSDK/releases/tag/1.0)
pelo navegador va para (http://IP_MAQUINA_XAMPP/DIRETORIO DO PROJETO)
ex: (http://192.168.1.24/ps4remote)

defina o ip do ps4 clicando no menu pagina escolha um pkg e instale.
E possivel instalar apartir do navegador tanto do ps4 quanto do pc.
![image](https://github.com/user-attachments/assets/bea0499f-6966-4dee-9667-fabf45b52cb8)












