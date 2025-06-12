/**
 * File Upload
 */

'use strict';

(function () {
 // previewTemplate: Updated Dropzone default previewTemplate
  //! Don't change it unless you really know what you are doing
  const previewTemplate = `<div class="dz-preview dz-file-preview">
<div class="dz-details">
  <div class="dz-thumbnail">
    <img data-dz-thumbnail>
    <span class="dz-nopreview">No preview</span>
    <div class="dz-success-mark"></div>
    <div class="dz-error-mark"></div>
    <div class="dz-error-message"><span data-dz-errormessage></span></div>
    <div class="progress">
      <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
    </div>
  </div>
  <div class="dz-filename" data-dz-name></div>
  <div class="dz-size" data-dz-size></div>
</div>
</div>`;

  // ? Start your code from here

    var dropzone = new Dropzone(".dropzone-images", {
        url: '/app/artist/upload-additional-images',
        method: "post",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        previewTemplate: previewTemplate,
        parallelUploads: 1,
        maxFilesize: 5,
        addRemoveLinks: true
      });
    // Add a success event handler
    dropzone.on("success", function(file, response) {
        if (response.error) {
            file.previewTemplate.remove();
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            toastr.error(response.error[0]);
        }
    });
})();
