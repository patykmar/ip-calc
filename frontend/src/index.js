import React from 'react';
import ReactDOM from 'react-dom';
import './index.css';
import {Route, BrowserRouter as Router, NavLink} from 'react-router-dom'
import reportWebVitals from './reportWebVitals';
import Ipv4Layout from "./components/ipcalc/Ipv4Layout";
import Ipv6Layout from "./components/ipcalc/Ipv6Layout";

const routing = (
    <Router>
        <header className="d-flex justify-content-center py-3">
            <ul className="nav nav-pills">
                <li className="nav-item"><NavLink className="nav-link" exact activeStyle={{class: 'active'}} to="/">IPv4</NavLink></li>
                <li className="nav-item"><NavLink className="nav-link" exact activeStyle={{class: 'active'}} to="ipv6">IPv6</NavLink></li>
            </ul>
        </header>
        <div>
            <Route exact path="/" component={Ipv4Layout} />
            <Route path="/ipv4" component={Ipv4Layout} />
            <Route path="/ipv6" component={Ipv6Layout} />
        </div>
    </Router>
);

ReactDOM.render(
    <React.StrictMode>
        {routing}
    </React.StrictMode>,
    document.getElementById('root'));

// If you want to start measuring performance in your app, pass a function
// to log results (for example: reportWebVitals(console.log))
// or send to an analytics endpoint. Learn more: https://bit.ly/CRA-vitals
reportWebVitals();
