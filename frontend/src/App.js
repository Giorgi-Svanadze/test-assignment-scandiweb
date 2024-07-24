import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import ProductList from './components/ProductList';
import AddProduct from './components/AddProduct';

const App = () => {
    return (
        <Router>
            <Routes>
                <Route exact path="/" element={<ProductList />} />
                <Route path="/add-product" element={<AddProduct />} />
            </Routes>
        </Router>
    );
};

export default App;
