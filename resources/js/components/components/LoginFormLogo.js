import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min';

import React from 'react';

const style = {
    backgroundImage: 'url("http://openagros.test/images/openagro_f-01.jpg")',
    backgroundPosition: 'center',
    backgroundSize: '100%',
    backgroundRepeat: 'no-repeat',
    minHeight: '85vh'
};

const LoginFormLogo = () => {
    return (
        <div className="col-md-8" style={style}>
        </div>
    );
}

export default LoginFormLogo;