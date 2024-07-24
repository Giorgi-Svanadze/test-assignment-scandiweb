import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import axios from 'axios';
import '../styles/AddProduct.scss';

const apiUrl = process.env.REACT_APP_API_URL || 'http://scandiweb-test.great-site.net/backend/api/';

const AddProduct = () => {
    const [product, setProduct] = useState({
        sku: '',
        name: '',
        price: '',
        type: '',
        size: '',
        weight: '',
        height: '',
        width: '',
        length: ''
    });
    const [error, setError] = useState('');
    const Navigate = useNavigate();

    const handleChange = (e) => {
        const { name, value } = e.target;
        setProduct({ ...product, [name]: value });
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        if (!product.sku || !product.name || !product.price || !product.type) {
            setError('Please, submit required data');
            return;
        }

        if (product.type === 'DVD' && !product.size) {
            setError('Please, provide the size');
            return;
        }
        if (product.type === 'Book' && !product.weight) {
            setError('Please, provide the weight');
            return;
        }
        if (product.type === 'Furniture' && (!product.height || !product.width || !product.length)) {
            setError('Please, provide the dimensions');
            return;
        }

        axios.post(`${apiUrl}products.php`, product)
            .then(response => {
                Navigate('/');
            })
            .catch(error => {
                if (error.response && error.response.data && error.response.data.message) {
                    setError(error.response.data.message);
                } else {
                    setError('An error occurred. Please try again.');
                }
            });
    };

    const handleCancel = () => {
        Navigate('/');
    };

    return (
        <div>
            <header>
                <h1>Add Product</h1>
                <nav>
                    <button type="submit" form="product_form">Save</button>
                    <button type="button" onClick={handleCancel}>Cancel</button>
                </nav>
            </header>
            <section>
                <form id="product_form" onSubmit={handleSubmit}>
                    <div>
                        <select id="productType" name="type" value={product.type} onChange={handleChange}>
                            <option value="">Select Type</option>
                            <option value="DVD">DVD</option>
                            <option value="Book">Book</option>
                            <option value="Furniture">Furniture</option>
                        </select>
                        <div>
                            <input type="text" id="sku" name="sku" placeholder="SKU" value={product.sku} onChange={handleChange} />
                            <input type="text" id="name" name="name" placeholder="Name" value={product.name} onChange={handleChange} />
                            <input type="text" id="price" name="price" placeholder="Price" value={product.price} onChange={handleChange} />
                        </div>
                    </div>
                    {product.type === 'DVD' && (
                        <div>
                            <label>Please, provide size</label>
                            <div>
                                <input type="text" id="size" name="size" placeholder="Size (MB)" value={product.size} onChange={handleChange} />
                            </div>
                        </div>
                    )}
                    {product.type === 'Book' && (
                        <div>
                            <label>Please, provide weight</label>
                            <div>
                                <input type="text" id="weight" name="weight" placeholder="Weight (Kg)" value={product.weight} onChange={handleChange} />
                            </div>
                        </div>
                    )}
                    {product.type === 'Furniture' && (
                        <div>
                            <label>Please, provide dimensions</label>
                            <div>
                                <input type="text" id="height" name="height" placeholder="Height" value={product.height} onChange={handleChange} />
                                <input type="text" id="width" name="width" placeholder="Width" value={product.width} onChange={handleChange} />
                                <input type="text" id="length" name="length" placeholder="Length" value={product.length} onChange={handleChange} />
                            </div>
                        </div>
                    )}
                </form>
                {error && <p>{error}</p>}
            </section>
            <footer>
                <p>Scandiweb Test Assignment</p>
            </footer>
        </div>
    );
};

export default AddProduct;