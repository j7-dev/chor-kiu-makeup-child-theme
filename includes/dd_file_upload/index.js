(function ($) {
    // dropzone event handlers
    const dropzones = document.querySelectorAll(".dropZone");

    const { avatars } = userData;

    const sendAjax = (files) => {
        let formData = new FormData();
        formData.append("nonce", userData.nonce);
        formData.append("action", userData.action);
        formData.append('user_avatar', files[0]);
        formData.append('user_id', userData.user_id);
        $.blockUI({ message: '上傳圖片中...請勿關閉視窗...' });

        fetch(userData.ajax_url, {
            method: "POST",
            credentials: "same-origin",
            dataType: 'json',
            body: formData,
        }).then((response) => {
            if (response.status == 200) {
                $.unblockUI();
                return response;
            }
        }).catch( error => {
            console.log(error);
        })
    };

    dropzones.forEach((dropzone, key) => {
        // init

        // 如果有圖片
        if (avatars[key]) {
            // make the jpeg image
            const preview = dropzone.querySelector(".preview");
            const newImg = new Image();
            //getRemoveImgBtn(dropzone);
            newImg.id = "newest";
            preview.appendChild(newImg);
            newImg.closest(".dropZone").classList.add("has-image");

            newImg.src = avatars[key];
        }

        //
        dropzone.addEventListener("dragenter", dragenter, false);
        dropzone.addEventListener("dragover", dragover, false);
        dropzone.addEventListener("drop", drop, false);
        dropzone.querySelector('input[type="file"]').addEventListener("change", handleFileSelect, false);
    });

    //
    function dragenter(e) {
        e.stopPropagation();
        e.preventDefault();
    }
    //

    function dragover(e) {
        e.stopPropagation();
        e.preventDefault();
    }

    //
    function drop(e) {
        e.stopPropagation();
        e.preventDefault();

        const dt = e.dataTransfer;
        const files = dt.files;
        const dropzone = this;
        handleFiles(files, dropzone);
        sendAjax(files);
    }

    //
    function handleFiles(files, dropzone) {
        const preview = dropzone.querySelector(".preview");
        preview.innerHTML = "";
        for (let i = 0; i < files.length; i++) {
            // get the next file that the user selected
            const file = files[i];
            const imageType = /image.*/;

            // don't try to process non-images
            if (!file.type.match(imageType)) {
                continue;
            }

            // a seed img element for the FileReader
            const img = document.createElement("img");
            img.classList.add("obj");
            img.file = file;

            // get an image file from the user
            // this uses drag/drop, but you could substitute file-browsing
            const reader = new FileReader();
            reader.onload = (function (aImg) {
                return function (e) {
                    aImg.onload = function () {
                        // draw the aImg onto the canvas
                        const canvas = document.createElement("canvas");
                        const ctx = canvas.getContext("2d");
                        canvas.width = aImg.width;
                        canvas.height = aImg.height;
                        ctx.drawImage(aImg, 0, 0);

                        // make the jpeg image
                        const newImg = new Image();
                        getRemoveImgBtn(dropzone);
                        newImg.onload = function () {
                            newImg.id = "newest";
                            preview.appendChild(newImg);
                            //preview.appendChild(removeImgBtn);
                            this.closest(".dropZone").classList.add("has-image");
                        };
                        newImg.src = canvas.toDataURL("image/jpeg", 1);
                    };
                    // e.target.result is a dataURL for the image
                    aImg.src = e.target.result;
                };
            })(img);
            reader.readAsDataURL(file);
        } // end for
    } // end handleFiles
    function handleFileSelect(e) {
        const files = e.target.files;
        const dropzone = this.closest(".dropZone");
        handleFiles(files, dropzone);
        sendAjax(files);
    }

    function getRemoveImgBtn(dropzone) {
        const preview = dropzone.querySelector(".preview");
        const removeImgBtn = document.createElement("i");
        removeImgBtn.classList.add("fa-light", "fa-xmark");
        removeImgBtn.addEventListener("click", () => {
            preview.innerHTML = "";
            dropzone.classList.remove("has-image");
            dropzone.querySelector('input[type="hidden"]').value = "";
        });
        preview.appendChild(removeImgBtn);
    }


})(jQuery);
