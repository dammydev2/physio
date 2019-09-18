<?php declare(strict_types=1);
/*
 * This file is part of resource-operations.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\ResourceOperations;

final class ResourceOperations
{
    /**
     * @return string[]
     */
    public static function getFunctions(): array
    {
        return [
            'Directory::close',
            'Directory::read',
            'Directory::rewind',
            'DirectoryIterator::openFile',
            'FilesystemIterator::openFile',
            'Gmagick::readimagefile',
            'HttpResponse::getRequestBodyStream',
            'HttpResponse::getStream',
            'HttpResponse::setStream',
            'Imagick::pingImageFile',
            'Imagick::readImageFile',
            'Imagick::writeImageFile',
            'Imagick::writeImagesFile',
            'MongoGridFSCursor::__construct',
            'MongoGridFSFile::getResource',
            'MysqlndUhConnection::stmtInit',
            'MysqlndUhConnection::storeResult',
            'MysqlndUhConnection::useResult',
            'PDF_activate_item',
            'PDF_add_launchlink',
            'PDF_add_locallink',
            'PDF_add_nameddest',
            'PDF_add_note',
            'PDF_add_pdflink',
            'PDF_add_table_cell',
            'PDF_add_textflow',
            'PDF_add_thumbnail',
            'PDF_add_weblink',
            'PDF_arc',
            'PDF_arcn',
            'PDF_attach_file',
            'PDF_begin_document',
            'PDF_begin_font',
            'PDF_begin_glyph',
            'PDF_begin_item',
            'PDF_begin_layer',
            'PDF_begin_page',
            'PDF_begin_page_ext',
            'PDF_begin_pattern',
            'PDF_begin_template',
            'PDF_begin_template_ext',
            'PDF_circle',
            'PDF_clip',
            'PDF_close',
            'PDF_close_image',
            'PDF_close_pdi',
            'PDF_close_pdi_page',
            'PDF_closepath',
            'PDF_closepath_fill_stroke',
            'PDF_closepath_stroke',
            'PDF_concat',
            'PDF_continue_text',
            'PDF_create_3dview',
            'PDF_create_action',
            'PDF_create_annotation',
            'PDF_create_bookmark',
            'PDF_create_field',
            'PDF_create_fieldgroup',
            'PDF_create_gstate',
            'PDF_create_pvf',
            'PDF_create_textflow',
            'PDF_curveto',
            'PDF_define_layer',
            'PDF_delete',
            'PDF_delete_pvf',
            'PDF_delete_table',
            'PDF_delete_textflow',
            'PDF_encoding_set_char',
            'PDF_end_document',
            'PDF_end_font',
            'PDF_end_glyph',
            'PDF_end_item',
        