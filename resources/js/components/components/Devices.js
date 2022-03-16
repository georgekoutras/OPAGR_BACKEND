import React, { useState } from "react";
import {NavLink} from "react-router-dom";
import DevicesItem from "./DevicesItem";

import '../../../sass/app.scss';

const DUMMY_DEVICES = [
    {id: '1', name: 'device1', state: 'active', rockblock_module: 'true', imei: 'xxx', gprs_module: 'true', gprs_id : 'xxx', iccid: 'xxx', msisdn: 'xxx', imsi: 'xxx'},
    {id: '2', name: 'device2', state: 'active', rockblock_module: 'true', imei: 'xxx', gprs_module: 'true', gprs_id : 'xxx', iccid: 'xxx', msisdn: 'xxx', imsi: 'xxx'},
    {id: '3', name: 'dvice3', state: 'active', rockblock_module: 'true', imei: 'xxx', gprs_module: 'true', gprs_id : 'xxx', iccid: 'xxx', msisdn: 'xxx', imsi: 'xxx'}
];

const Devices = (props) => {

    // const [devices, setDevices] = useState();
    //
    // useEffect(async () => {
    //     try {
    //         const response = await fetch('/get-devices');
    //
    //         if (!response.ok) {
    //             throw new Error('Εντοπίστηκε σφάλμα');
    //         }
    //         else {
    //             const data = await response.json();
    //             console.log(data)
    //         }
    //     }
    //     catch(error) {
    //         console.log('error: ' + error.message);
    //     }
    //
    // }, []);

    const [devices, setDevices] = useState(DUMMY_DEVICES);

    return (
        <div className={props.className}>
            <div className="row font-weight-bold">
                <div className="col-3 ">Όνομα</div>
                <div className="col-3">IMEI</div>
                <div className="col-3">GPRS_ID</div>
                <div className="col-3">ICCID</div>
            </div>
            { devices.map((device) => (
                <DevicesItem {...device} key={device.id}/>
            ))
            }
        </div>
    );
}

export default Devices;