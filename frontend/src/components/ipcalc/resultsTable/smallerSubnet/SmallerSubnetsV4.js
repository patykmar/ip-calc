import React from "react";
import styles from './SmallerSubnetsV4.module.css';
import {Button} from "react-bootstrap";

class SmallerSubnetsV4 extends React.Component {

    smallSubnetButtonClickHandler = (event) =>{
        this.props.onClickHandlerButton(event.target.value);
    }

    render = () => {
        console.log('SmallerSubnetsV4 - render');
        console.log(this.props.smallerSubnetsData);
        return (
            <div>
                {Object.values(this.props.smallerSubnetsData).map((smallerSubnet, index) => (
                    <div className={styles.smallerSubnetCard} key={index}>
                        <ol>
                            {Object.values(smallerSubnet).map((subnet, indexx) => (
                                <li><Button onClick={this.smallSubnetButtonClickHandler} key={index + '-' + indexx} variant="link"
                                            value={subnet.subnet}>{subnet.subnet}</Button></li>
                            ))}
                        </ol>
                    </div>
                ))}
            </div>
        );
    }
}

export default SmallerSubnetsV4;