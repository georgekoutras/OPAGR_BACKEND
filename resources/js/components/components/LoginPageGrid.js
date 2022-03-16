import React from 'react';

import LoginFormLogo from './LoginFormLogo';
import LoginFormContainer from './LoginFormContainer';

const Main = () => {

    return (
        <div className="row align-items-center vh-80">
            <LoginFormLogo/>
            <LoginFormContainer/>
        </div>
    );
}

export default Main;