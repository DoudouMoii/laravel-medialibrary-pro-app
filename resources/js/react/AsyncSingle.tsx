import * as React from 'react';
import MediaLibraryClass from '../../../vendor/spatie/laravel-medialibrary-pro/ui//medialibrary-pro-core';
import MediaLibraryAttachment from '../../../vendor/spatie/laravel-medialibrary-pro/ui/medialibrary-pro-react-attachment';

export default function AsyncSingle() {
    function submit() {
        // This can also be placed in the `afterUpload` prop of the component

        /* TODO:
        axios.post(…)
        .catch(errors => {
            // setValidationErrors({ [value[0].uuid]: ['kek'] });
            // format errors to looks like this: { "uuid": ["error"]}
            setValidationErrors(formattedErrors);
        });
        */
    }

    const [value, setValue] = React.useState(window.oldValues.media);
    const [validationErrors, setValidationErrors] = React.useState<MediaLibraryClass['validationErrors']>();

    return (
        <div>
            <MediaLibraryAttachment
                name="media"
                uploadEndpoint={window.uploadEndpoint}
                translations={{
                    hint: { singular: 'Add a file!', plural: 'Add some files!' },
                    replace: 'drag or click to replace',
                }}
                validation={{ accept: ['image/png'], maxSize: 1048576 }}
                validationErrors={validationErrors}
                initialValue={value}
                onChange={setValue}
            ></MediaLibraryAttachment>

            <button
                className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-2"
                onClick={submit}
            >
                Submit
            </button>
        </div>
    );
}
