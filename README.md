
# Collaborative Supermarket Offer Sharing Platform

This project implements a collaborative platform where users can register, submit, validate, and evaluate product offers from various supermarkets, including local stores and mini-markets. It complements official price comparison tools (e.g., e-katanalotis) by adding user-driven information such as stock availability and offer verification.

## 🧑‍💻 User Roles

- **Administrator**: Manages product, store, and pricing data; views statistics and leaderboards; deletes offers.
- **User**: Registers, views and submits offers, interacts via map, evaluates offers, and earns points.

## 🚀 Features

### User Capabilities
- **Registration/Login**: Secure password requirements and account management.
- **Map Interaction**:
  - Real-time location-based offer display.
  - Filter by store name or product category.
  - View store markers and offers with price, validation stats (likes/dislikes), and stock status.
- **Offer Evaluation**:
  - If within 50 meters of a store, user can rate offers (like/dislike/out-of-stock).
  - Additional product and submitter info (photo, username, score) available.
- **Submit New Offer**:
  - Select store and product category.
  - Set offer price and validate based on price history.
  - One active offer per product/store unless new price is 20% lower.
- **Monthly Token Reward System**:
  - Users gain tokens based on score each month (80% of total distributed proportionally).
- **Scoring System**:
  - +50 points: price 20% lower than previous day avg.
  - +20 points: price 20% lower than weekly avg.
  - +5 points: for each like.
  - -1 point: for each dislike.
- **Profile & History**:
  - View/edit profile, submission history, likes/dislikes, and token history.

### Admin Capabilities
- **Product & Pricing Management**:
  - Upload/update/delete category and product data (JSON/XML).
  - Product pricing updates via JSON import.
- **Store Management**:
  - Upload/update/delete supermarket POIs using OpenStreetMap APIs or manual import.
- **Statistics**:
  - Graph: offers per day by month.
  - Graph: average discount per category/subcategory by week.
- **Leaderboard**:
  - View top users by score with pagination and token stats.
- **Advanced Map Access**:
  - Same as users with additional offer deletion capabilities.

## 🛠️ Technologies Used

- Frontend: Responsive web interface (desktop & mobile)
- Backend: REST APIs (not detailed here)
- Data Sources: JSON from [e-katanalotis.gov.gr](https://e-katanalotis.gov.gr/products/navbar)
- Map APIs: OpenStreetMap, Nominatim, Overpass API
- Language Support: Python scripts, web technologies

## 📁 Data Management

- Product & price data: JSON/XML
- Offers expire after 7 days unless refreshed
- Token pool generated monthly and distributed
- Secure, permission-based UI per user type

## 📌 Notes

This system is designed to encourage community validation and transparency in everyday shopping, enhancing trust and reducing misinformation through decentralized participation.
