# Webshop API

This repository contains a Webshop API developed in Laravel. The API provides essential backend functionality for a webshop, including product management, categories, price lists, contract-based pricing, and order handling.

## Features
- **Products**: Each product can belong to multiple categories and appear in price lists with varying prices.
- **Categories**: Includes support for multi-level hierarchy. Products can be linked to one or more categories.
- **Price Lists**: Defined alternate pricing for products based on different price lists. Overrides default product prices. Each product may exist in multiple price lists with unique prices.
- **Contract-Based Pricing**: Enables user-specific pricing that overrides prices from price lists and default prices. Links users to specific products with negotiated prices.
- **Price modifiers**: Allow dynamic adjustments to individual product prices or the total order price, providing flexibility for discounts, taxes, and custom pricing rules.
- **Orders**: Stores customer orders with: total order price (with tax modifiers and potential discounts), list of purchased products and prices, customer details: Name, Email, Phone, Address, City, and Country.

## Endpoints

- List all products with pagination
- List products by category with pagination
- View a single product
- Filter and sort products:

    - Filter by price, name, and category.
    - Sort by price or name (ascending/descending).
    - Adjustments for price lists and contract-based pricing.

- Create orders: Accepts an array of products to create a new order.
