import React from "react";
import {Link} from 'react-router-dom'
import styles from './SmallerSubnetsV4.module.css';

class SmallerSubnetsV4 extends React.Component {

    render = () => {
        console.log('SmallerSubnetsV4 - render');
        console.log(this.props.smallerSubnetsData);
        return (
            <div>
                {Object.values(this.props.smallerSubnetsData).map((smallerSubnet, index) => (
                    <div className={styles.smallerSubnetCard} key={index}>
                        <ol>
                            {Object.values(smallerSubnet).map((subnet, indexx) => (
                                <li key={index + '-' + indexx}><Link
                                    to={`/ipv4/${subnet.subnet}`}>{subnet.subnet}</Link></li>
                            ))}
                        </ol>
                    </div>
                ))}
            </div>
        );
    }
}

export default SmallerSubnetsV4;