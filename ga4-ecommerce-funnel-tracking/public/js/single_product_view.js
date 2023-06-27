jQuery(document).ready(function () {
  console.log("Product page loaded");
  //   dataLayer.push({ ecommerce: null });
  //   window.dataLayer = [];
  dataLayer.push({
    event: "single_product_view",
    ecommerce: {
      itemId: data["item_id"],
      itemName: data["item_name"],
      discount: data["discount"],
      itemBrand: data["item_brand"],
      itemCategory: data["item_category"],
      price: data["price"],
    },
  });
});
