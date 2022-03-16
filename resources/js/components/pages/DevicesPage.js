import React, {useEffect} from 'react';

import Layout from "../components/Layout";
import Devices from "../components/Devices";

const DevicesPage = () => {

    return (
        <Layout>
            <h5 className="mb-5">Συσκευές</h5>

            <Devices className="container-fluid data-table-striped data-table"/>

        </Layout>
    )
}

export default DevicesPage;