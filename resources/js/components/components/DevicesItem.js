import React from 'react';

const DeviceItem = (props) => {
    return (
        <div className="row" key={props.id}>
            <div className="col-3">{props.name}</div>
            <div className="col-3">{props.imei}</div>
            <div className="col-3">{props.gprs_id}</div>
            <div className="col-3">{props.iccid}</div>
        </div>
    );
}

export default DeviceItem;