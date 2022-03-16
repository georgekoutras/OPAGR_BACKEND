import React from 'react';

import LoginPageGrid from '../components/LoginPageGrid';
import Footer from '../components/Footer';

function LoginPage() {
    return (
        <div className="container-fluid">
            <LoginPageGrid />
            <Footer />
        </div>
    );
}

export default LoginPage;