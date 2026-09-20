<div class="modal fade" id="modal-order-{{ $pedido->id }}" tabindex="-1" aria-labelledby="modalOrderLabel{{ $pedido->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.pedidos.update', $pedido) }}" data-lock-on-submit>
                @csrf
                @method('PUT')
                <input type="hidden" name="_modal" value="modal-order-{{ $pedido->id }}">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalOrderLabel{{ $pedido->id }}">Pedido #{{ $pedido->id }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>

                <div class="modal-body" x-data="pedidoModal()" x-init="cargar(@js($pedido->load('items.product.primaryImage')))">
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small">Nombre</label>
                            <input type="text" name="customer_name" value="{{ $pedido->customer_name }}" class="form-control form-control-sm" {{ $pedido->isDelivered() ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Teléfono</label>
                            <input type="text" name="customer_phone" value="{{ $pedido->customer_phone }}" class="form-control form-control-sm" {{ $pedido->isDelivered() ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Estado</label>
                            <select name="status" class="form-select form-select-sm">
                                @foreach (['PENDIENTE', 'EN_PROCESO', 'ENVIADO', 'ENTREGADO'] as $s)
                                    <option value="{{ $s }}" {{ $pedido->status === $s ? 'selected' : '' }}>{{ $s }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label small">Zona</label>
                            <select name="delivery_zone" class="form-select form-select-sm" {{ $pedido->isDelivered() ? 'disabled' : '' }}>
                                <option value="">Seleccionar</option>
                                <option value="lima" {{ $pedido->delivery_zone === 'lima' ? 'selected' : '' }}>Lima</option>
                                <option value="provincias" {{ $pedido->delivery_zone === 'provincias' ? 'selected' : '' }}>Provincias</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Ciudad</label>
                            <input type="text" name="delivery_city" value="{{ $pedido->delivery_city }}" class="form-control form-control-sm" {{ $pedido->isDelivered() ? 'disabled' : '' }}>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Dirección</label>
                            <input type="text" name="delivery_address" value="{{ $pedido->delivery_address }}" class="form-control form-control-sm" {{ $pedido->isDelivered() ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Referencias / Notas de entrega</label>
                        <textarea name="delivery_notes" class="form-control form-control-sm" rows="2" {{ $pedido->isDelivered() ? 'disabled' : '' }}>{{ $pedido->delivery_notes }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small">Nota interna (admin)</label>
                        <textarea name="notes" class="form-control form-control-sm" rows="2" {{ $pedido->isDelivered() ? 'disabled' : '' }}>{{ $pedido->notes }}</textarea>
                    </div>

                    <hr class="my-3">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Items del pedido <span class="text-muted small">(Total: <span x-text="formatCurrency(total)"></span>)</span></label>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 50px;">Img</th>
                                        <th>Producto</th>
                                        <th style="width: 120px;">Precio unit.</th>
                                        <th style="width: 100px;">Cantidad</th>
                                        <th style="width: 120px;">Subtotal</th>
                                        <th style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template x-for="(item, index) in items" :key="item.id || 'new-' + index">
                                        <tr>
                                            <td>
                                                <template x-if="item.image">
                                                    <img :src="item.image" alt="" class="order-item-thumb" loading="lazy">
                                                </template>
                                                <template x-if="!item.image">
                                                    <div class="order-item-thumb bg-light d-flex align-items-center justify-content-center"><x-icon name="image" class="icon-sm text-muted" /></div>
                                                </template>
                                            </td>
                                            <td class="fw-medium" x-text="item.product_name"></td>
                                            <td class="text-muted" x-text="formatCurrency(item.unit_price)"></td>
                                            <td>
                                                <input type="number" class="form-control form-control-sm text-center"
                                                       :name="'items[' + index + '][quantity]'"
                                                       x-model.number="item.quantity"
                                                       @input="recalculate(index)"
                                                       min="1"
                                                       {{ $pedido->isDelivered() ? 'disabled' : '' }}>
                                                <input type="hidden" :name="'items[' + index + '][id]'" :value="item.id || ''">
                                                <input type="hidden" :name="'items[' + index + '][product_id]'" :value="item.product_id">
                                                <input type="hidden" :name="'items[' + index + '][unit_price]'" :value="item.unit_price">
                                            </td>
                                            <td class="fw-medium" x-text="formatCurrency(item.subtotal)"></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" @click="removeItem(index)" {{ $pedido->isDelivered() ? 'disabled' : '' }} title="Eliminar item">
                                                    <x-icon name="trash" class="icon-sm" />
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        @if (!$pedido->isDelivered())
                        <div class="mt-2 d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <x-icon name="plus" class="icon-sm" /> Agregar item
                            </button>
                        </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <div class="me-auto text-muted small">
                            Total: <strong x-text="formatCurrency(total)"></strong>
                        </div>
                    </div>

                    @if (!$pedido->isDelivered())
                    <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addItemModalLabel">Agregar producto al pedido</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Buscar producto</label>
                                        <div class="input-group">
                                            <span class="input-group-text"><x-icon name="search" class="icon-sm" /></span>
                                            <input type="text" class="form-control" placeholder="Nombre o código..." x-model="searchQuery" @input.debounce.300ms="searchProducts()">
                                        </div>
                                    </div>
                                    <div class="table-responsive" style="max-height: 300px;">
                                        <table class="table table-sm table-hover align-middle mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 50px;">Img</th>
                                                    <th>Producto</th>
                                                    <th style="width: 120px;">Precio</th>
                                                    <th style="width: 100px;">Stock</th>
                                                    <th style="width: 80px;"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <template x-for="product in searchResults" :key="product.id">
                                                    <tr>
                                                        <td>
                                                            <template x-if="product.primary_image_url">
                                                                <img :src="product.primary_image_url" alt="" class="order-item-thumb">
                                                            </template>
                                                            <template x-if="!product.primary_image_url">
                                                                <div class="order-item-thumb bg-light d-flex align-items-center justify-content-center"><x-icon name="image" class="icon-sm text-muted" /></div>
                                                            </template>
                                                        </td>
                                                        <td class="fw-medium" x-text="product.name"></td>
                                                        <td x-text="formatCurrency(product.price)"></td>
                                                        <td class="text-muted" x-text="product.stock"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-sm btn-primary" @click="addItem(product)">
                                                                <x-icon name="plus" class="icon-sm" /> Agregar
                                                            </button>
                                                        </td>
                                                    </tr>
                                                </template>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" {{ $pedido->isDelivered() ? 'disabled' : '' }}>
                        <x-icon name="save" class="icon-sm" /> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
