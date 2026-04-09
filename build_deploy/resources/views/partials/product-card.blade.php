<div class="col-md-6 col-lg-3 ftco-animate">
  <div class="product">
    <a href="{{ route('product.single', ['slug' => $product['slug']]) }}" class="img-prod">
      <img class="img-fluid" src="{{ asset('assets/images/' . $product['image']) }}" alt="{{ $product['name'] }}">
      @if (!empty($product['discount']))
        <span class="status">{{ $product['discount'] }}</span>
      @endif
      <div class="overlay"></div>
    </a>
    <div class="text py-3 pb-4 px-3 text-center">
      <h3><a href="{{ route('product.single', ['slug' => $product['slug']]) }}">{{ $product['name'] }}</a></h3>
      <div class="d-flex">
        <div class="pricing">
          @if (!empty($product['sale_price']))
            <p class="price"><span class="mr-2 price-dc">{{ $product['price'] }}</span><span class="price-sale">{{ $product['sale_price'] }}</span></p>
          @else
            <p class="price"><span>{{ $product['price'] }}</span></p>
          @endif
        </div>
      </div>
      <div class="bottom-area d-flex px-3">
        <div class="m-auto d-flex">
          <a href="{{ route('product.single', ['slug' => $product['slug']]) }}" class="add-to-cart d-flex justify-content-center align-items-center text-center">
            <span><i class="ion-ios-menu"></i></span>
          </a>
          <a href="{{ route('cart') }}" class="buy-now d-flex justify-content-center align-items-center mx-1">
            <span><i class="ion-ios-cart"></i></span>
          </a>
          <a href="{{ route('wishlist') }}" class="heart d-flex justify-content-center align-items-center">
            <span><i class="ion-ios-heart"></i></span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
