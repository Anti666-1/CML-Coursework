document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('search_line');
  const musicContainer = document.getElementById('music-container');

  searchInput.addEventListener('keyup', function(event) {
      if (event.key === 'Enter') {
          performSearch();
      }
  });

  function createAudioTrack(songFile) {
      const music = document.getElementById('niz');
      const audio = document.createElement('audio');
      audio.controls = true;
      audio.src = songFile;
      audio.type = 'audio/mpeg';
      audio.addEventListener('error', (event) => {
          console.error('Ошибка загрузки аудио:', event);
          alert('Не удалось загрузить аудиофайл.');
      });

      music.innerHTML = '';
      music.appendChild(audio);
  }

  function attachPlayButtonListeners() {
      const playButtons = document.querySelectorAll('.play-button');

      playButtons.forEach(button => {
          button.addEventListener('click', function(event) {
              event.preventDefault();

              const songFile = this.getAttribute('data-song-file');
              createAudioTrack(songFile);
          });
      });
  }

  function performSearch() {
      const searchTerm = searchInput.value.trim().toLowerCase();
      let showAllSongs = false;

      if (!searchTerm) {
          showAllSongs = true;
      }

      fetch('GLOBAL_DATA.xml')
          .then(response => response.text())
          .then(xmlString => {
              const parser = new DOMParser();
              const xmlDoc = parser.parseFromString(xmlString, 'text/xml');
              const songs = xmlDoc.querySelectorAll('song');

              let resultsHTML = '';
              songs.forEach(song => {
                  const songName = song.querySelector('song_name').textContent.toLowerCase();
                  const artistName = song.closest('artist').querySelector('artist_name').textContent.toLowerCase();
                  const albumName = song.closest('album').querySelector('album_name').textContent.toLowerCase();
                  const genreName = song.closest('genre').querySelector('genre_name').textContent.toLowerCase();
                  const albumId = song.closest('album').getAttribute('id');

                  if (showAllSongs ||
                    songName.includes(searchTerm) ||
                    artistName.includes(searchTerm) ||
                    albumName.includes(searchTerm) ||
                    genreName.includes(searchTerm)) {

                    const songFile = song.querySelector('file').textContent;

                    resultsHTML += `
                        <div class="songs">
                <div class="song-info">
                  <div class="song-name">${song.querySelector('song_name').textContent}</div>
                  <a href="album.php?album_id=${albumId}">
                  <div class="artist-name">${song.closest('artist').querySelector('artist_name').textContent} - ${song.closest('album').querySelector('album_name').textContent}</div>
                  </a>
                  </div>
                <div class="song-actions">
                  <a href="#" class="play-button" data-song-file="${songFile}">
                    <svg viewBox="0 -3 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#191d1e"
                            stroke="#89b6b9" transform="translate(0,-3)"/>
                      <!-- Окружность -->
                      <path d="M10 5.5v9l6 -4.5-6 -4.5z" fill="#89b6b9" transform="translate(-6,-6) scale(1.5)"/>
                    </svg>
                  </a>

                  <a href="${songFile}" download class="download-button">
                    <svg viewBox="0 -3 24 24">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z" fill="#191d1e"
                            stroke="#89b6b9" transform="translate(0,-3)"/>
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
          border-bottom: 1px solid #5d7070;
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
                `;
  
            }
          });
  
          if (resultsHTML === '') {
            resultsHTML = '<p style="color: white">Ничего не найдено.</p>';
        }

        musicContainer.innerHTML = resultsHTML;
        attachPlayButtonListeners();
    })
    .catch(error => {
        console.error('Ошибка при загрузке XML:', error);
        musicContainer.innerHTML = '<p style="color: white">Ошибка при загрузке данных.</p>';
    });
}

performSearch();
});