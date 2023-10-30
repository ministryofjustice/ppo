<?php

$id = get_the_ID();
$document_type = get_the_terms($id, 'document_type');
$document_type = $document_type ?? $document_type[0]->slug;

$document_date = get_post_meta($id, 'document-date', true);
$document_upload = get_post_meta($id, 'document-upload', true);

?>
<article id="<?= 'doc-' . $id ?>" class="<?= esc_attr($document_type) ?>">
    <a href="<?= $document_upload ?>" target="_blank">
        <div class="tile-image">
            <?php

            // Show post thumbnail
            if (has_post_thumbnail()) {
                the_post_thumbnail('document-thumb');
            } // Fallback to the attachment thumbnail
            else {
                $attachment_id = get_post_meta($id, 'document-upload-attachment-id', true);
                if ($attachment_id) {
                    echo wp_get_attachment_image($attachment_id, 'document-thumb');
                }
            }

            ?>
        </div>
        <div class="tile-details">
            <h3><?php the_title(); ?></h3>
            <div class="tile-published-date">Published: <?= $document_date ?></div>
            <?= file_meta($document_upload) ?>
        </div>
    </a>
</article>
