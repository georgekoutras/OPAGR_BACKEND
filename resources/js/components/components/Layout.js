import React, {useState} from "react";
import GridRow from "../UI/GridRow";
import Menu from "./Menu";

import '../../../sass/app.scss';

const Layout = (props) => {

    const [showUserOptions, setShowUserOptions] = useState(false);

    const usernameClickHandler = (event) => {
        event.preventDefault();
        setShowUserOptions((prevState) => !prevState);
    }

    return (
        <div className="container-fluid">
            <GridRow style={{height: '100vh'}}>
                <div className="col-md-2">
                <aside >
                    <div>
                        <img src="http://openagros.test/storage/openagro_f-01.jpg" className="logo" alt="logo"/>
                    </div>
                    <Menu />
                </aside>
                </div>
                <div className="col-md-10">
                    <header className="d-flex">
                        {/*<div className="mt-4 mr-5 ml-auto">*/}
                        {/*    <div className="d-flex">*/}
                        {/*        <a src="" className="ml-auto" onClick={usernameClickHandler}>user</a>*/}
                        {/*    </div>*/}
                        {/*    {showUserOptions &&*/}
                        {/*        <div className="user-options">*/}
                        {/*            <div>*/}
                        {/*                <span>Προφίλ</span>*/}
                        {/*            </div>*/}
                        {/*            <div>*/}
                        {/*                <span>Αποσύνδεση</span>*/}
                        {/*            </div>*/}
                        {/*        </div>*/}
                        {/*    }*/}
                        {/*</div>*/}
                        <div className="dropdown ml-auto user-options text-right">

                            <a href="" className="dropdown-toggle no-after peers fxw-nw ai-c lh-1"
                               data-toggle="dropdown">
                                    <img className="w-2r bdrs-50p" src="" alt=""/>
                                    <span className="fsz-md c-grey-900">user</span>
                            </a>

                            <ul className="dropdown-menu fsz-sm">
                                <li>
                                    <a href="/logout" className="d-b td-n pY-5 bgcH-grey-100 c-grey-700">
                                        <i className="ti-power-off mR-10"></i>
                                        <span>Έξοδος</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </header>
                    <main>{props.children}</main>
                </div>
            </GridRow>
        </div>
    );
}

export default Layout