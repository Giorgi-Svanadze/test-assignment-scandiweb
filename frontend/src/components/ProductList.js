import React, { useEffect, useState } from 'react';
import axios from 'axios';
import { Link } from 'react-router-dom';
import '../styles/ProductList.scss';

const apiUrl = process.env.REACT_APP_API_URL || 'http://scandiweb-test.great-site.net/backend/api/';

const ProductList = () => {
    const [products, setProducts] = useState([]);
    const [selectedProducts, setSelectedProducts] = useState([]);

    useEffect(() => {
        axios.get(`${apiUrl}products.php`)
            .then(response => setProducts(response.data))
            .catch(error => console.error('Error fetching products:', error));
    }, []);

    const handleCheckboxChange = (sku) => {
        if (selectedProducts.includes(sku)) {
            setSelectedProducts(selectedProducts.filter(item => item !== sku));
        } else {
            setSelectedProducts([...selectedProducts, sku]);
        }
    };

    const handleMassDelete = () => {
        axios.post(`${apiUrl}delete_products.php`, { skus: selectedProducts })
            .then(response => {
                console.log(response.data);
                setProducts(products.filter(product => !selectedProducts.includes(product.sku)));
                setSelectedProducts([]);
            })
            .catch(error => console.error('Error deleting products:', error));
    };

    return (
        <div>
            <header>
                <h1>Product List</h1>
                <nav>
                    <Link to="/add-product">
                        <button>ADD</button>
                    </Link>
                    <button onClick={handleMassDelete}>MASS DELETE</button>
                </nav>
            </header>
            <section>
                <ul>
                    {products.map(product => (
                        <li key={product.sku}>
                            <input
                                type="checkbox"
                                className="delete-checkbox"
                                checked={selectedProducts.includes(product.sku)}
                                onChange={() => handleCheckboxChange(product.sku)}
                            />
                            <p>SKU: {product.sku}</p>
                            <p>Name: {product.name}</p>
                            <p>Price: ${product.price}</p>
                            <p>{product.specificAttribute}</p>
                        </li>
                    ))}
                </ul>
            </section>
            <footer>
                <p>Scandiweb Test Assignment</p>
            </footer>
        </div>
    );
};

export default ProductList;
