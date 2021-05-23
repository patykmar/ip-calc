import React from "react";

export default class Button extends React.Component {
    render() {
        if (this.props.onClick) {
            return (
                <button
                    className="btn btn-lg btn-primary"
                    type={this.props.type || 'button'}
                    onClick={this.props.onClick}
                >
                    {this.props.children}
                </button>
            );
        } else {
            return (
                <button
                    className="btn btn-lg btn-primary"
                    type={this.props.type || 'button'}>
                    {this.props.children}
                </button>
            );
        }
    }
}