<?php
$album_id = $_GET['album_id'] ?? null;

if ($album_id === null) {
    exit;
}

$xml = simplexml_load_file('GLOBAL_DATA.xml');

function findAlbumById($xml, $album_id) {
    foreach ($xml->genre as $genre) {
        foreach ($genre->artist as $artist) {
            foreach ($artist->album as $album) {
                if ((string)$album['id'] === $album_id) {
                    return $album;
                }
            }
        }
    }
    return null;
}

$album = findAlbumById($xml, $album_id);

if ($album === null) {
    exit;
}

$country = (string)$album->xpath('../country')[0];
$genre_name = (string)$album->xpath('../../genre_name')[0];
$artist_name = (string)$album->xpath('../artist_name')[0];
$album_name = (string)$album->album_name;
$image = (string)$album->image;
$year = (string)$album->year;
$info = (string)$album->xpath('../info')[0];

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($album_name); ?></title>
    <link rel="stylesheet" href="Oformlenie_main_album.css">
</head>
<body>
<main class="grid">
        <header style="grid-area: head;">
            <div id="logo"></div>
            <nav id="verh">
                <div id="v1">
                <span id="ghost">Ghost Forest</span>
                <span id="free">Скачивайте музыку бесплатно!</span>
                </div>
                <div id="v2">
                <span id="cab">Личный кабинет</span>
                <span id="genres">Жанры
                    <ul>
                        <li id="genre">
                         <ul id="pod">
                          <li id="v"><a href="">Avant-Garde Black Metal</a></li>
                          <li id="v"><a href="">Alternative Rock</a></li>
                          <li id="v"><a href="">Atmospheric Black Metal</a></li>
                          <li id="v"><a href="">Dark Ambient</a></li>
                          <li id="v"><a href="">Dark Metal</a></li>
                          <li id="v"><a href="DSBM.html">DS Black Metal</a></li>
                          <li id="v"><a href="">NS Black Metal</a></li>
                          <li id="v"><a href="">NDH</a></li>
                          <li id="v"><a href="">Noise</a></li>
                          <li id="v"><a href="">Psychodelic</a></li>
                          <li id="v"><a href="">Symphonic metal</a></li>
                          <li id="v"><a href="">Trve Black Metal</a></li>
                         </ul>
                        </li>
                      </ul>
                </span>
                </div>
            </nav>
        </header>
    <div class="song-item" id="music-container" style="grid-area: cont;">
        <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($album_name); ?>" class="album-cover">
    <div class="all-info">
        <h1><?php echo htmlspecialchars($album_name); ?></h1>
        <h2><?php echo htmlspecialchars($artist_name); ?></h2>
        <div class="year"><span id="ob">Страна: </span><?php echo htmlspecialchars($country); ?></div>
        <div class="year"><span id="ob">Жанр: </span><?php echo htmlspecialchars($genre_name); ?></div>
        <span class="year"><span id="ob">Год выпуска: </span><?php echo htmlspecialchars($year); ?></span>
        <p class="album-info"><?php echo htmlspecialchars($info); ?></p>
    </div>
    <span id="ob">Песни:</span>
<?php
    if (isset($album) && isset($album->song) && is_iterable($album->song)) { // Проверка существования и итерируемости
        foreach ($album->song as $song) {
            echo '
                    <div class="songs">
                        <div class="song-info">
                            <div class="song-name">'. htmlspecialchars($song->song_name) .' ('. htmlspecialchars($song->length) .')</div>
                            <div class="artist-name">'.htmlspecialchars($artist_name). ' - ' .htmlspecialchars($album->album_name).'</div>
                        </div>
                        <div class="song-actions">
                            <a href="#" class="play-button" data-song-file="'.htmlspecialchars($song->file).'">
                                <svg viewBox="0 -3 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#191d1e" stroke="#89b6b9" transform="translate(0,-3)"/>
                                    <path d="M10 5.5v9l6 -4.5-6 -4.5z" fill="#89b6b9" transform="translate(-6,-6) scale(1.5)"/>
                                </svg>
                            </a>
                            <a href="'.htmlspecialchars($song->file).'" download class="download-button">
                                <svg viewBox="0 -3 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#191d1e" stroke="#89b6b9" transform="translate(0,-3)"/>
                                    <path d="M19 9h-4V3H9v6H5l7 7 7-7z" fill="#89b6b9"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                     <style>
      .songs {
          width: 100%;
          height: 30px; 
          padding: 10px;
          padding-right: 10px;
          border-top: 1px solid #5d7070;
          display: flex;
          justify-content: space-between; /
          align-items: center; 
      }
  a{
  text-decoration: none;
  }
      .song-info {
          display: flex;
          flex-direction: column;
          justify-content: center;
      }
  
      .song-actions {
          display: flex;
          align-items: center; 
          margin-right: 20px;
      }
  
      .artist-name {
          color: #89b6b9;
          font-size: 0.8em
      }
  
      .song-name {
          color: white;
          font-size: 1em; 
      }
  
      .download-button,
      .play-button {
          width: 30px;
          height: 30px; 
          margin: 0 5px; 
          display: flex; 
          align-items: center;
          justify-content: center; 
      }
  
      .download-button svg,
      .play-button svg {
          width: 100%;
          height: 100%;
      }
  
  audio {
    margin-top: 2%;
    margin-right: 12px;
    width: 100%; 
    height: 40px; 
    background-color: #5d7070;
    border-radius: 20px; 
    outline: #7d9797 4px ridge; 
  }
  audio::-webkit-media-controls-panel {
    background-color: #5d7070; /* Цвет панели управления */
    border-radius: 20px;
  }
  
  @media (max-width: 480px) {
  .songs {
  height: 40px}
  }
  </style>   
                ';
        }
    } else {
        echo "<li>No songs found.</li>"; 
    }
?>
    </div>
    <aside style="grid-area: genre;">
    <ul>
        <li><a href="">Avant-Garde Black Metal</a></li>
        <li><a href="">Alternative Rock</a></li>
        <li><a href="">Atmospheric Black Metal</a></li>
        <li><a href="">Dark Ambient</a></li>
        <li><a href="">Dark Metal</a></li>
        <li><a href="DSBM.html">DS Black Metal</a></li>
        <li><a href="">NS Black Metal</a></li>
        <li><a href="">NDH</a></li>
        <li><a href="">Noise</a></li>
        <li><a href="">Psychodelic</a></li>
        <li><a href="">Symphonic metal</a></li>
        <li><a href="">Trve Black Metal</a></li>
    </ul>
</aside>
<footer style="grid-area: niz;" id="niz">
</footer>
</main>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOMContentLoaded event fired");

    const musicContainer = document.getElementById('music-container');
    const music = document.getElementById('niz');

    function createAudioTrack(songFile) {
        if (music.firstChild && music.firstChild instanceof HTMLMediaElement) {
            music.firstChild.pause();
        }

        const audio = document.createElement('audio');
        audio.controls = true;
        audio.src = songFile;
        audio.type = 'audio/mpeg';
        audio.addEventListener('error', (event) => {
            console.error('Ошибка загрузки аудио:', event);
            console.log('Не удалось загрузить аудиофайл: ' + songFile);
        });

        music.innerHTML = '';
        music.appendChild(audio);
    }

    function attachPlayButtonListeners() {
        console.log("attachPlayButtonListeners called");
        const playButtons = document.querySelectorAll('.play-button');

        playButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const songFile = this.getAttribute('data-song-file');
                console.log("Play button clicked, songFile: ", songFile);
                createAudioTrack(songFile);
            });
        });
    }

    attachPlayButtonListeners();
});
</script>
</body>
</html>