   let filaActual = null;
        let ratingSeleccionado = 0;

        document.querySelectorAll('.btn-aprobar').forEach(btn => {
            btn.addEventListener('click', function() {
                filaActual = this.closest('.fila-cuerpo');
                
                const wrapperEstrellas = filaActual.querySelector('.estrellas-wrapper');
                const estrellasRellenas = wrapperEstrellas.querySelectorAll('.bi-star-fill').length;
                
                const comentarioEle = filaActual.querySelector('.col-comentario');
                const textoComentario = comentarioEle ? comentarioEle.textContent.trim() : "";
                
                document.getElementById('modal-comentario-text').value = textoComentario;
                actualizarEstrellasModal(estrellasRellenas);
                
                document.getElementById('modal-editar').classList.remove('hidden');
            });
        });

        function actualizarEstrellasModal(rating) {
            ratingSeleccionado = rating;
            document.querySelectorAll('.star-modal').forEach((star, index) => {
                if (index < rating) {
                    star.className = 'bi bi-star-fill star-modal';
                } else {
                    star.className = 'bi bi-star-black star-modal';
                }
            });
        }

        document.querySelectorAll('.star-modal').forEach((star, index) => {
            star.addEventListener('click', () => {
                actualizarEstrellasModal(index + 1);
            });
        });

        document.getElementById('btn-cancelar-modal').addEventListener('click', () => {
            document.getElementById('modal-editar').classList.add('hidden');
        });

        document.getElementById('btn-guardar-modal').addEventListener('click', () => {
            if (!filaActual) return;
            
            const wrapperEstrellas = filaActual.querySelector('.estrellas-wrapper');
            wrapperEstrellas.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                const estrella = document.createElement('i');
                if (i < ratingSeleccionado) {
                    estrella.className = 'bi bi-star-fill star';
                } else {
                    estrella.className = 'bi bi-star-black star-empty';
                }
                wrapperEstrellas.appendChild(estrella);
            }
            
            const nuevoTexto = document.getElementById('modal-comentario-text').value.trim();
            let comentarioEle = filaActual.querySelector('.col-comentario');
            
            if (nuevoTexto !== "") {
                if (!comentarioEle) {
                    comentarioEle = document.createElement('span');
                    comentarioEle.className = 'col-comentario';
                    filaActual.querySelector('.col-puntuacion').appendChild(comentarioEle);
                }
                comentarioEle.textContent = nuevoTexto;
            } else {
                if (comentarioEle) comentarioEle.remove();
            }
            
            document.getElementById('modal-editar').classList.add('hidden');
        });