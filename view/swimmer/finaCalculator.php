<section class="card p-1 row tab" id="fina-calculator">
    <header class="col-12">
        <h2>Calculadora de puntos FINA</h2>
    </header>
    <main class=" col-12 mt-2">
        <form class="form-race" id="mark-points" action="mark/finaPoints" method="post">
            <div class="row">
            <div>
                <div>Categoría</div>
                    <select name="category" class="category" required>
                        <option disabled selected value="">Categoría</option>
                        <option value="+20">+20</option>
                        <option value="+25">+25</option>
                        <option value="+30">+30</option>
                        <option value="+35">+35</option>
                        <option value="+40">+40</option>
                        <option value="+45">+45</option>
                        <option value="+50">+50</option>
                        <option value="+55">+55</option>
                        <option value="+60">+60</option>
                        <option value="+65">+65</option>
                        <option value="+70">+70</option>
                        <option value="+75">+75</option>
                        <option value="+80">+80</option>
                        <option value="+85">+85</option>
                        <option value="+90">+90</option>
                        <option value="+95">+95</option>
                    </select>
                </div>
                <div>
                    <div>Género</div>
                    <select name="gender" class="gender" required>
                        <option disabled selected value="">Género</option>
                        <option value="male">Hombre</option>
                        <option value="female">Mujer</option>
                    </select>
                </div>
                <div>
                    <div>Piscina</div>
                    <select name="pool" class="pool" required>
                        <option disabled selected value="">Piscina</option>
                        <option value="25m">25m</option>
                        <option value="50m">50m</option>
                    </select>
                </div>
                <div>
                    <div>Estilo</div>
                    <select name="style" class="style" required>
                        <option disabled selected value="">Estilo</option>
                        <option value="backstroke">Espalda</option>
                        <option value="breaststroke">Braza</option>
                        <option value="butterfly">Mariposa</option>
                        <option value="freestyle">Libre</option>
                        <option value="medley">Estilos</option>
                    </select>
                </div>
                <div>
                    <div>Distancia</div>
                    <select name="distance" class="distance" required>
                        <option disabled selected value="">Distancia</option>
                    </select>
                </div>
            </div>
            <div class="row mt-1">
                <div>
                    <div>Marca</div>
                    <div id="mark">
                        <input type="number" class="min" name="min" min="0" max="99" step="1" placeholder="min" value="<?php if (isset($data['content']['mark']['min'])) echo $data['content']['mark']['min']; ?>">
                        :
                        <input type="number" class="sec" name="sec" min="0" max="59" step="1" placeholder="seg" value="<?php if (isset($data['content']['mark']['sec'])) echo $data['content']['mark']['sec']; ?>">
                        .
                        <input type="number" class="dec" name="dec" min="0" max="99" step="1" placeholder="cent" value="<?php if (isset($data['content']['mark']['dec'])) echo $data['content']['mark']['dec']; ?>">
                    </div>
                </div>
                <div>
                    <div>Puntos FINA</div>
                    <div>
                        <input id="finaPoints" type="number" min="0" max="9999" class="response" name="finaPoints" value="<?php if (isset($data['content']['points'])) echo $data['content']['points']; ?>">
                    </div>
                </div>
            </div>
            <input type="hidden" name="swimmerId" value="<?php echo $_SESSION['id']; ?>">
            <div class="row mt-1">
                <button type="submit" class="btn" ajax-request='{ "url" : "mark/finaPoints/v" }'>
                    Calcular
                </button>
            </div>
            <div class="error mt-1" id="error"><?php if (isset($data['content']['error'])) echo $data['content']['error']; ?></div>
        </form>
    </main>
    <footer class="mt-1">
        <p>*Puntos basados en récords del mundo absolutos a junio de 2023
        <p>
    </footer>
</section>