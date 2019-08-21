<?php
 //--------------------------------
 // CREATE WATERMARK FUNCTION
 //--------------------------------

 define( 'WATERMARK_OVERLAY_IMAGE', 'watermark.png' );
 define( 'WATERMARK_OVERLAY_IMAGE_THUMB', 'watermark_thumb.png' );
 define( 'WATERMARK_OVERLAY_OPACITY', 50 );
 define( 'WATERMARK_OUTPUT_QUALITY', 80 );

 function create_watermark( $source_file_path, $output_file_path )
 {
  list( $source_width, $source_height, $source_type ) = getimagesize( $source_file_path );

  if ( $source_type === NULL )
  {
   return false;
  }

  switch ( $source_type )
  {
   case IMAGETYPE_GIF:
    $source_gd_image = imagecreatefromgif( $source_file_path );
    break;
   case IMAGETYPE_JPEG:
    $source_gd_image = imagecreatefromjpeg( $source_file_path );
    break;
   case IMAGETYPE_PNG:
    $source_gd_image = imagecreatefrompng( $source_file_path );
    break;
   default:
    return false;
  }

  $overlay_gd_image = imagecreatefrompng( WATERMARK_OVERLAY_IMAGE_THUMB );
  $overlay_width = imagesx( $overlay_gd_image );
  $overlay_height = imagesy( $overlay_gd_image );

  imagecopymerge(
   $source_gd_image,
   $overlay_gd_image,
   $source_width - $overlay_width,
   $source_height - $overlay_height,
   0,
   0,
   $overlay_width,
   $overlay_height,
   WATERMARK_OVERLAY_OPACITY
  );

  imagejpeg( $source_gd_image, $output_file_path, WATERMARK_OUTPUT_QUALITY );

  imagedestroy( $source_gd_image );
  imagedestroy( $overlay_gd_image );
 }

 //--------------------------------
 // FILE PROCESSING FUNCTION
 //--------------------------------
 
 define( 'UPLOADED_IMAGE_DESTINATION', 'images/' );
 define( 'PROCESSED_IMAGE_DESTINATION', 'originals/' );

 function process_image_upload()
 {
 foreach (glob(UPLOADED_IMAGE_DESTINATION."*_tn.jpg") as $filename) {	
  $temp_file_path = $filename;
echo $filename."<br>";
  list( , , $temp_type ) = getimagesize( $temp_file_path );

  if ( $temp_type === NULL )
  {
   return false;
  }

  switch ( $temp_type )
  {
   case IMAGETYPE_GIF:
    break;
   case IMAGETYPE_JPEG:
    break;
   case IMAGETYPE_PNG:
    break;
   default:
    return false;
  }

  $uploaded_file_path = UPLOADED_IMAGE_DESTINATION . basename($filename);
  $processed_file_path = PROCESSED_IMAGE_DESTINATION . preg_replace( '/\\.[^\\.]+$/', '.jpg', basename($filename) );

 // move_uploaded_file( $temp_file_path, $uploaded_file_path );

  $result = create_watermark( $uploaded_file_path, $processed_file_path );
 }
   
 }
 //--------------------------------
 // END OF FUNCTIONS
 //--------------------------------

 $result = process_image_upload();


  echo 'ok';
?>
