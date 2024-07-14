import { __ } from '@wordpress/i18n';
import { registerBlockType } from '@wordpress/blocks';
import { RichText, MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button } from '@wordpress/components';

// Required for sidebar options
import { InspectorControls } from '@wordpress/block-editor';
import { TextControl, PanelBody } from '@wordpress/components';


registerBlockType('my-plugin/my-custom-block', {
    title: __('My Custom Block', 'text-domain'),
    icon: 'smiley',
    category: 'common',
    attributes: {
        content: {
            type: 'string',
            selector: 'h5',
            default: 'File Title'
        },
        pdfUrl: {
            type: 'string',
            default: ''
        },
        thumbnailUrl: {
            type: 'string',
            default: ''
        },
        customText: {
            type: 'string',
            selector: '.custom-text',
            default: ''
        }
    },

    // This is how its displayed in the Admin
    edit: (props) => {
        const { attributes: { content, pdfUrl, thumbnailUrl, customText }, setAttributes, className } = props;

        const onSelectPDF = (media) => {
            const pdfUrl = media.url;
            let thumbnailUrl = '';
            // console.log('Checking block.');
            // Check if media details include sizes and 'thumbnail' size exists
            if (media.sizes && media.sizes.thumbnail) {
                thumbnailUrl = media.sizes.thumbnail.url;
            } else {
                // Fallback if no thumbnail size is available
                thumbnailUrl = media.icon || ''; // Use media.icon as a generic fallback
            }
            setAttributes({ 
                pdfUrl: pdfUrl,
                thumbnailUrl: thumbnailUrl,
                customText: customText 
            });
        };

        // update sidebar options
        const onChangeCustomText = (newText) => {
            setAttributes({ customText: newText });
        };

        return (
            <div className="user-download-block">
                <RichText
                    tagName="h5"
                    className={ className }
                    onChange={ (newContent) => setAttributes({ content: newContent }) }
                    value={ content }
                    placeholder={ __('File title', 'text-domain') }
                />
                <MediaUploadCheck>
                    <MediaUpload
                        onSelect={ onSelectPDF }
                        allowedTypes={['application/pdf']}
                        value={ pdfUrl } // Media ID (optional)
                        render={({ open }) => (
                            <Button onClick={ open }>
                                { pdfUrl ? 'Change PDF' : 'Upload PDF' }
                            </Button>
                        )}
                    />
                </MediaUploadCheck>
                <InspectorControls>
                    <PanelBody title="Custom Text Settings">
                        <TextControl
                            label="Custom Text"
                            value={customText}
                            onChange={onChangeCustomText}
                            placeholder={ __('Description', 'text-domain') }

                        />
                    </PanelBody>
                </InspectorControls>
                { pdfUrl && thumbnailUrl && (
                    <a href={pdfUrl} className="download-link" target="_blank">
                        <img src={thumbnailUrl} alt="PDF Thumbnail" />Download Now
                    </a>
                )}
                { pdfUrl && !thumbnailUrl && (
                    <a href={pdfUrl} className="download-link" target="_blank">Download Now</a>
                )}
            </div>
        );
    },

    // this is whats saved in the Admin and will display on frontend after save
    save: ({ attributes }) => {
        const { content, pdfUrl, thumbnailUrl, customText } = attributes;
        return (
            <div className="user-download-block">
                <RichText.Content
                    tagName="h5"
                    className="file-title"
                    value={content}
                />
                <RichText.Content
                    tagName="p"
                    className="additional-text"
                    value={customText}
                />
                { pdfUrl && thumbnailUrl && (
                    <a href={pdfUrl} className="download-link" target="_blank">
                        <img src={thumbnailUrl} alt="PDF Thumbnail" />Download Now
                    </a>
                )}
                { pdfUrl && !thumbnailUrl && (
                    <a href={pdfUrl} className="download-link" target="_blank">Download Now</a>
                )}
            </div>
        );
    },

});
