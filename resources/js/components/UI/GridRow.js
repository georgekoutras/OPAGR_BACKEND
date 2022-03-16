import React from 'react';

const GridRow = (props) => {
    return (
        <div className="row" style={props.style}>
            {props.children}
        </div>
    );
}

export default GridRow;