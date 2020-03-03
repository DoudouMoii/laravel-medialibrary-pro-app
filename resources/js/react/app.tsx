import * as React from 'react';
import ReactDOM from 'react-dom';
import MedialibraryClass from '@spatie/medialibrary-pro-core';
import UploadComponent from './UploadComponent';

declare const window: {
    oldValues: { name: string; media: MedialibraryClass['state']['media'] };
    errors: { [key: string]: string };
    tempEndpoint: string;
};

ReactDOM.render(
    <div>
        <UploadComponent
            name="media"
            initialValue={window.oldValues.media}
            errors={window.errors}
            tempEndpoint={window.tempEndpoint} /* Get from blade */
        ></UploadComponent>
    </div>,
    document.getElementById('app')
);
