import React from 'react';

const Search = (props) => {
		
        return (
            <div className="container">			
                
                {/*Search Input*/}
                <label className="search-label" htmlFor="search-input">
                    <input
                        type="text"
                        value={props.value}
                        id="search-input"
                        placeholder="searching name, dmg or stat"
                        onChange={props.onSearch}
                    />
                    <i className="fa fa-search search-icon"/>
                </label>
                
            </div>
            )	
    }

export default Search;