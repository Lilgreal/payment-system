import React, {Component} from 'react';

class Cross extends Component {
    render() {
        return (
            <svg {...this.props} version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 426.667 426.667">
                <path d="M213.333,0C95.514,0,0,95.514,0,213.333s95.514,213.333,213.333,213.333s213.333-95.514,213.333-213.333S331.153,0,213.333,0z M330.995,276.689l-54.302,54.306l-63.36-63.356l-63.36,63.36l-54.302-54.31l63.356-63.356l-63.356-63.36l54.302-54.302l63.36,63.356l63.36-63.356l54.302,54.302l-63.356,63.36L330.995,276.689z"/>
            </svg>
        );
    }
}

export default Cross;