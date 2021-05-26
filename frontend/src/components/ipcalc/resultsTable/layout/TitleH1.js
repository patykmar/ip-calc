import React from "react";
import {Col, Row} from "react-bootstrap";

class TitleH1 extends React.Component{

    render() {
        return(
            <Row>
                <Col>
                    <h1 className="display-4 fw-bold lh-1 mb-3">{this.props.children}</h1>
                </Col>
            </Row>
        );
    }

}

export default TitleH1;